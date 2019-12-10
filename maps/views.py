from django.shortcuts import render, redirect
from django.core.files.storage import FileSystemStorage

from .forms import IncidentForm

# Create your views here.
def mapIndex(request):
    return render(request, "maps/map_index.html", {})

def reportIncident(request):
    if request.method == "POST":
        form = IncidentForm(request.POST, request.FILES)
        if form.is_valid():
            post = form.save(commit=False)
            post.userID = request.user
            post.save()
            return redirect("successful_report")

    else:
        form = IncidentForm()

    return render(request, "maps/reportIncident.html",
                  {'form': form, "succes": False})

def successful_report(request):
    return render(request, "maps/successfulIncident.html", {})
