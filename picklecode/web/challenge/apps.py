from django.apps import AppConfig
from django.db import connection


class ChallengeConfig(AppConfig):
    name = 'challenge'

    def ready(self):
        connection.creation.create_test_db(keepdb=True)
