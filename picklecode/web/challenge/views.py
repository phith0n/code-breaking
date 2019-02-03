from django.http.response import HttpResponse, HttpResponseRedirect
from django.template import engines
from django.contrib.auth import login as auth_login, get_user_model, authenticate
from django.contrib.auth.views import LoginView, logout_then_login
from django.contrib.auth.decorators import login_required
from django.views import generic

User = get_user_model()


@login_required
def index(request):
    django_engine = engines['django']
    template = django_engine.from_string('My name is ' + request.user.username)
    return HttpResponse(template.render(None, request))


class RegistrationLoginView(LoginView):
    def post(self, request, *args, **kwargs):
        """
        Handle POST requests: instantiate a form instance with the passed
        POST variables and then check if it's valid.
        """
        form = self.get_form()
        if form.is_valid():
            return self.form_valid(form)

        if 'username' not in form.cleaned_data or 'password' not in form.cleaned_data:
            return self.form_invalid(form)

        if User.objects.filter(username=form.cleaned_data['username']).exists():
            return self.form_invalid(form)

        user = User.objects.create_user(form.cleaned_data['username'], None, form.cleaned_data['password'])
        auth_login(request, user)
        return HttpResponseRedirect(self.get_success_url())
