// initial libraries
const Koa = require('koa')
const sqlite = require('sqlite')
const fs = require('fs')
const views = require('koa-views')
const Router = require('koa-router')
const send = require('koa-send')
const bodyParser = require('koa-bodyparser')
const session = require('koa-session')
const isString = require('underscore').isString
const basename = require('path').basename

const config = JSON.parse(fs.readFileSync('../config.json', {encoding: 'utf-8', flag: 'r'}))

async function main() {
    const app = new Koa()
    const router = new Router()
    const db = await sqlite.open(':memory:')

    await db.exec(`CREATE TABLE "main"."users" (
        "id" INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
        "username" TEXT NOT NULL,
        "password" TEXT,
        CONSTRAINT "unique_username" UNIQUE ("username")
    )`)
    await db.exec(`CREATE TABLE "main"."flags" (
        "id" INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
        "flag" TEXT NOT NULL
    )`)
    for (let user of config.users) {
        await db.run(`INSERT INTO "users"("username", "password") VALUES ('${user.username}', '${user.password}')`)
    }
    await db.run(`INSERT INTO "flags"("flag") VALUES ('${config.flag}')`)

    router.all('login', '/login/', login).get('admin', '/', admin).get('static', '/static/:path(.+)', static).get('/source', source)

    app.use(views(__dirname + '/views', {
        map: {
            html: 'underscore'
        },
        extension: 'html'
    })).use(bodyParser()).use(session(app))
    
    app.use(router.routes()).use(router.allowedMethods());
    
    app.keys = config.signed
    app.context.db = db
    app.context.router = router
    app.listen(3000)
}

function safeKeyword(keyword) {
    if(isString(keyword) && !keyword.match(/(union|select|;|\-\-)/is)) {
        return keyword
    }

    return undefined
}

async function login(ctx, next) {
    if(ctx.method == 'POST') {
        let username = safeKeyword(ctx.request.body['username'])
        let password = safeKeyword(ctx.request.body['password'])

        let jump = ctx.router.url('login')
        if (username && password) {
            let user = await ctx.db.get(`SELECT * FROM "users" WHERE "username" = '${username.toUpperCase()}' AND "password" = '${password.toUpperCase()}'`)

            if (user) {
                ctx.session.user = user

                jump = ctx.router.url('admin')
            }

        }

        ctx.status = 303
        ctx.redirect(jump)
    } else {
        await ctx.render('index')
    }
}

async function static(ctx, next) {
    await send(ctx, ctx.path)
}

async function admin(ctx, next) {
    if(!ctx.session.user) {
        ctx.status = 303
        return ctx.redirect(ctx.router.url('login'))
    }

    await ctx.render('admin', {
        'user': ctx.session.user
    })
}

async function source(ctx, next) {
    await send(ctx, basename(__filename))
}

main()
