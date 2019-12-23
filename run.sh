cd CRITr
python3 manage.py makemigrations # --dry-run --verbosity 3
python3 manage.py migrate
python3 manage.py runserver 0.0.0.0:8000
