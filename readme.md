# Laravel benchmark environment
## Introduction

This project is a simple benchmark to measure the performance of the Laravel framework.

When the server is running, the following URLs are available:

- /bench1.php: PHP without framework, simple echo response
- /bench2.php: PHP without framework, insert row into local mysql db
- /bench3: Laravel, simple echo response
- /bench4: Laravel, insert row into local mysql db

The code for the first two can be found in `public/`, the last two in `app/routes.php`.

N.b.: this is not intended as a scientific benchmark. All comments (improvements) are welcome at pesterhazy@gmail.com.

## Setup

This repository holds everything you need to run the benchmarks. You only need to be able to spin up an EC2 instance, as well as the required credentials to SSH into that instance.

## Instructions

### Requirements

- Install ansible
  - For Debian/Ubuntu:
  - `sudo apt-get install python-pip`
  -  `sudo pip install paramiko PyYAML jinja2 httplib2 ansible`
  - for other systems: http://docs.ansible.com/intro_installation.html

### Spin up an ec2 instance

- Ubuntu 12.04 64-bit
- On eu-west-1, use AMI: `ami-55c92422`
- Using the EC2 console
  - Launch Instance
  - Community AMIs
  - In the search box, enter ami-55c92422
  - Select `m3.medium`
  - Make sure you're using a security group that allows you to connect using SSH; optionally enable HTTP (port 80).
- Get its public dns name: ec2-xx-yy-zz-kk.eu-west-1.compute.amazonaws.com
- Test that you can connect using SSH
  - `ssh -lubuntu ec2-xx-yy-zz-kk.eu-west-1.compute.amazonaws.com`

### Provision the instance

- `cp provision/inventory.example provision/inventory`
- edit `provision/inventory` and replace `insert.your.ec2.instance.here` with the public dns name of the ec2 instance
- `ansible-playbook -i provision/inventory provision/webservers.yml`

### Running the benchmark

The benchmark is run automatically as the last step of the provisioning. You can also run the benchmarks manually:

    cd /var/www/laravel
    bash benchmark.sh
 
The resulting numbers are written to `public/benchmark.csv`. If you have enabled HTTP access (port 80) to the instance in the EC2 security group, you can access the benchmark by opening this URL in your browser:

  http://ec2-xx-yy-zz-kk.eu-west-1.compute.amazonaws.com/benchmark.csv

## Results

Here are the results of a run on an `m3.medium` instance

    url;requests per second
    bench1.php;1621.42
    bench2.php;461.15
    bench3;36.24
    bench4;25.46

In other words, inserting a single row can be performed **461.15 times** using a bare PHP script, but only **25.46 times** using a Laravel route.

## Conclusion

Using Laravel seems to add a significant performance penalty on very simple scripts.

It would be interesting to see if the performance can be improved easily. Comments welcome at pesterhazy@gmail.com
