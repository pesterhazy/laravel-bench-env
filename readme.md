## Instruction

Requirements

- Install ansible
  - For Debian/Ubuntu:
  - `sudo apt-get install python-pip`
  -  `sudo pip install paramiko PyYAML jinja2 httplib2 ansible`
  - for other systems: http://docs.ansible.com/intro_installation.html

Spin up an ec2 instance

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

Provision the instance

- `cp provision/inventory.example provision/inventory`
- edit `provision/inventory` and replace `insert.your.ec2.instance.here` with the public dns name of the ec2 instance
- `ansible-playbook -i provision/inventory provision/webservers.yml`
