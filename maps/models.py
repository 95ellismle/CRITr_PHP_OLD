from django.db import models
from django.conf import settings
from django.utils import timezone


# Create your models here.
class Incident(models.Model):
    incidentChoices = (("Littering", "Littering"),
                       ("Loitering","Loitering"),
                       ("Graffiti","Graffiti"),
                       ("Speeding","Speeding"),
                       ("Parking","Parking"),)

    incidentType = models.CharField(max_length=200, choices=incidentChoices)
    incidentTime = models.TimeField(default=timezone.now)
    incidentDate = models.DateField(default=timezone.now)
    latitude = models.FloatField()
    longitude = models.FloatField()
    x = models.FloatField()
    y = models.FloatField()
    details = models.TextField(null=True, blank=True)
    photoPath = models.ImageField(upload_to="incidentPhotos/%Y/%m/%d")
    timeSubmitted = models.DateTimeField(default=timezone.now)
    userID = models.ForeignKey(settings.AUTH_USER_MODEL,
                               on_delete=models.CASCADE)

    def publish(self):
        self.timeSubmitted = timezone.now()
        self.save()

    def __str__(self):
        return self.incidentType
