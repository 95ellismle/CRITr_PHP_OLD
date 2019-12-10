from django import forms

from . import models

class IncidentForm(forms.ModelForm):

    def __init__(self, *args, **kwargs):
        super(IncidentForm, self).__init__(*args, **kwargs)
        for key in ('incidentType', 'incidentTime', 'incidentDate',
                    'longitude', 'x', 'y', 'timeSubmitted',
                    'latitude',): #'userID',
            self.fields[key].required = True

        for key in ("details", "photoPath"):
            self.fields[key].required = False

    class Meta:
        model = models.Incident
        fields = ('incidentType', 'incidentTime', 'incidentDate',
                  'longitude', 'x', 'y', 'details', 'photoPath',
                  'timeSubmitted', 'latitude')#, 'userID',)
