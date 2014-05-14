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

This repository holds everything you need to run the benchmarks on EC2. You only need to be able to spin up an EC2 instance, as well as the required credentials to SSH into that instance.

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

The benchmark is run automatically as the last step of the provisioning. You can also run the benchmarks manually by `ssh`ing into the instance:

    cd /var/www/laravel
    bash benchmark.sh
 
The resulting numbers are written to `public/benchmark.csv`. If you have enabled HTTP access (port 80) to the instance in the EC2 security group, you can access the benchmark by opening this URL in your browser:

  http://ec2-xx-yy-zz-kk.eu-west-1.compute.amazonaws.com/benchmark.csv

## Results

Here are the results of a run on an `m3.medium` instance

    url;requests per second;workload
    bench1.php;1621.42;PHP without framework, simple echo response
    bench2.php;461.15;PHP without framework, insert row into local mysql db
    bench3;36.24;Laravel, simple echo response
    bench4;25.46;Laravel, insert row into local mysql db

In other words, inserting a single row can be performed **461.15 times per second** using a bare PHP script, but only **25.46 times per second** using a Laravel route.

A single request using the Laravel route `/bench4` takes about `22.779` ms, which is not too bad.

## Output

Here's the sample output of `ab -n1000 -c20 http://localhost/bench4`:

```
This is ApacheBench, Version 2.3 <$Revision: 655654 $>
Copyright 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
Licensed to The Apache Software Foundation, http://www.apache.org/

Benchmarking localhost (be patient)
Completed 100 requests
Completed 200 requests
Completed 300 requests
Completed 400 requests
Completed 500 requests
Completed 600 requests
Completed 700 requests
Completed 800 requests
Completed 900 requests
Completed 1000 requests
Finished 1000 requests


Server Software:        Apache/2.4.9
Server Hostname:        localhost
Server Port:            80

Document Path:          /bench4
Document Length:        27 bytes

Concurrency Level:      20
Time taken for tests:   18.512 seconds
Complete requests:      1000
Failed requests:        0
Write errors:           0
Total transferred:      755044 bytes
HTML transferred:       27000 bytes
Requests per second:    54.02 [#/sec] (mean)
Time per request:       370.236 [ms] (mean)
Time per request:       18.512 [ms] (mean, across all concurrent requests)
Transfer rate:          39.83 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   2.0      0      25
Processing:    32  362 595.9    290    6650
Waiting:       32  328 592.2    268    6649
Total:         32  362 596.0    291    6650

Percentage of the requests served within a certain time (ms)
  50%    291
  66%    335
  75%    382
  80%    438
  90%    564
  95%    725
  98%   1630
  99%   4135
 100%   6650 (longest request)

```

Here's the output without concurrency (`-c1`):

```
ab -n100 -c1 http://localhost/bench4
This is ApacheBench, Version 2.3 <$Revision: 655654 $>
Copyright 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
Licensed to The Apache Software Foundation, http://www.apache.org/

Benchmarking localhost (be patient).....done


Server Software:        Apache/2.4.9
Server Hostname:        localhost
Server Port:            80

Document Path:          /bench4
Document Length:        27 bytes

Concurrency Level:      1
Time taken for tests:   2.278 seconds
Complete requests:      100
Failed requests:        0
Write errors:           0
Total transferred:      75454 bytes
HTML transferred:       2700 bytes
Requests per second:    43.90 [#/sec] (mean)
Time per request:       22.779 [ms] (mean)
Time per request:       22.779 [ms] (mean, across all concurrent requests)
Transfer rate:          32.35 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.0      0       0
Processing:    12   23  20.7     20     166
Waiting:       12   22  20.4     19     162
Total:         12   23  20.7     20     166

Percentage of the requests served within a certain time (ms)
  50%     20
  66%     22
  75%     22
  80%     23
  90%     26
  95%     29
  98%    162
  99%    166
 100%    166 (longest request)
```
## Conclusion

Using Laravel seems to add a significant performance penalty on very simple scripts.

Are these numbers in line with what people expect? It would be interesting to see if and how the performance can be improved easily. Comments welcome at pesterhazy@gmail.com
