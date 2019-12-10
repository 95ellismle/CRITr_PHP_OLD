from django.urls import path
from . import views


urlpatterns = [
    path("", views.mapIndex, name="map_index"),
    path("reporting_an_incident", views.reportIncident, name="reportIncident"),
    path("success", views.successful_report, name="successful_report"),
]
