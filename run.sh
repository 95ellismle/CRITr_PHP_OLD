cd CRITr
#echo "from django.contrib.auth import get_user_model; User = get_user_model(); User.objects.create_superuser('admin_', 'password')" | python3 manage.py shell
python3 manage.py makemigrations # --dry-run --verbosity 3
python3 manage.py migrate
python3 manage.py runserver 0.0.0.0:8000
