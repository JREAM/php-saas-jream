import os
import sys
from fabric.api import *
from fabric.context_managers import cd
from fabric.contrib.files import exists, sed

# Nicer output I think
env.colorize_errors = True

# Should be default, but here if needed to change
env.ssh_config_path = '~/.ssh/config'

# Use ~/.ssh/config file
env.use_ssh_config = True

# Forward ~/.ssh/config_file agent for Git Access
env.forward_agent = True

# Definne Hostnames to use from ~/.ssh/config file
env.hosts = ['jream',]

# Where to go
destination = '/var/www/dev/jream.com'
repo = 'git@github.com:JREAM/jream.com'

@task
def deploy():
    with cd(destination):
        has_git = exists('.git/')
        if not has_git:
            run('git clone %s .' % repo)
        else:
            run('git pull')
            run('git submodule update --init --recursive')
