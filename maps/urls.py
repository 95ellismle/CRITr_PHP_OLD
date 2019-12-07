from django.urls import path
from . import views


urlpatterns = [
    path("", views.mapIndex, name="map_index"),
    path("reportIncident", views.reportIncident, name="reportIncident"),
]
