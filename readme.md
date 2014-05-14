## Instruction

Requirements

- Install ansible
  - For Debian/Ubuntu:
  - `sudo apt-get install python-pip`
  -  `sudo pip install paramiko PyYAML jinja2 httplib2 ansible`
  - for other systems: http://docs.ansible.com/intro_installation.html

Provisioning the instance

- Spin up an ec2 instance
- `cp provision/inventory.example provision/inventory`
- edit `provision/inventory` and replace `insert.your.ec2.instance.here` with the public dns name of the ec2 instance
- `ansible-playbook -i provision/inventory provision/webservers.yml`
