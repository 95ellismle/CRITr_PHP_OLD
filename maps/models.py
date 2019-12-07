from django.db import models
from django.conf import settings
from django.utils import timezone


# Create your models here.
class Incident(models.Model):
    incidentType = models.CharField(max_length=200)
    incidentTime = models.DateTimeField(default=timezone.now)
    latitude = models.FloatField()
    longitude = models.FloatField()
    x = models.FloatField()
    y = models.FloatField()
    details = models.TextField(null=True, blank=True)
    photoPath = models.FilePathField()
    timeSubmitted = models.DateTimeField(default=timezone.now)
    userID = models.CharField(max_length=200)

    def publish(self):
        self.timeSubmitted = timezone.now()
        self.save()

    def __str__(self):
        return self.incidentType
