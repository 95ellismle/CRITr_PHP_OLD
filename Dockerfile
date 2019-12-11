FROM python:3.8.0-buster

# Make a directory for the app
RUN mkdir /critr
ADD . /critr
WORKDIR /critr

# Install dependencies
COPY requirements.txt .
RUN pip3 install -r requirements.txt

# Copy the source code
COPY . /critr 

# Run the app
CMD ["./run.sh"]
