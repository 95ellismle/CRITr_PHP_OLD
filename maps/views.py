from django.shortcuts import render

# Create your views here.
def mapIndex(request):
    return render(request, "maps/map_index.html", {})

def reportIncident(request):
    return render(request, "maps/reportIncident.html", {})
