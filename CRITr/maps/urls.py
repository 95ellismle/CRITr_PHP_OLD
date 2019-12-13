from django.urls import path
from django.contrib.auth import views as auth_views
from django.conf.urls import url

from . import views


urlpatterns = [
    path("", views.mapIndex, name="map_index"),
    path("reporting_an_incident", views.reportIncident, name="reportIncident"),
    path("success", views.successful_report, name="successful_report"),
    path('login', auth_views.LoginView.as_view(), name='login'),
    url(r'^signup/$', views.signup, name='signup'),
    url(r'^ajax/validate_username/$', views.validate_username,
        name='validate_username'),
]
