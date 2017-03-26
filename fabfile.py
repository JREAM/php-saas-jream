from fabric.api import *
from fabric.operations import require
"""
Required:
---------
- Python Fabric Installed
- Remote Linux/MySQL/Git + SSH Setup

1: Add user to www-data file (or your Apache Group)
"""

# ------------------------------------------------------------------------------
# Settings
# ------------------------------------------------------------------------------
STAGES = {
    'production': {
        'hosts': [
            'jesse@23.92.28.20:2327',   # jream02
        ],
        'path': '/var/www/jream.com/htdocs/',
        'branch': 'master'
    },
    'dev': {
        'hosts': [
            'jesse@45.79.132.122:2327'  # dev00
        ],
        'path': '/var/www/dev.jream.com/htdocs/',
        'branch': 'dev'
    },
    'local': {
        'hosts': [
            '127.0.0.1'
        ],
        'path': '~/projects/jream.com',
        'branch': False
    }
}

CACHE_PATH = 'cache/'  # End with trailing "/"


def set_stage(stage_name):
    """Sets env.path, env.branch, etc variables
    """
    env.stage = stage_name
    env.key_filename = "/home/jesse/.ssh/id_rsa"
    for option, value in STAGES[env.stage].items():
        setattr(env, option, value)


@task
def production():
    set_stage('production')


@task
def dev():
    set_stage('dev')


@task
def local():
    set_stage('local')


# ------------------------------------------------------------------------------
# Server
# ------------------------------------------------------------------------------

@task
def rmcache():
    """Removes Cache
    """
    commands = [
        "redis-cli flushall",
        "find {0} -type f -name '*.volt.php' -exec rm -rf {{}} +".format(CACHE_PATH),
        "find {0} -type f -name '*.volt_e_.php' -exec rm -rf {{}} +".format(CACHE_PATH),
        "rm -rf {0}*%%.php".format(CACHE_PATH),
        "rm -rf {0}*.volt.php".format(CACHE_PATH)
    ]
    if env.stage is 'local':
        for cmd in commands:
            local(cmd)
    else:
        with cd(env.path):
            for cmd in commands:
                run(cmd)


@task
def composer():
    """Runs a composer update
    """
    with cd(env.path):
        run("composer update --prefer-dist --no-dev --optimize-autoloader")


@task
def permissions():
    """Updates the permissions in Linux
    """
    with cd(env.path):
        sudo('chown -R jesse:www-data *')
        sudo('chmod g+rw *')
        sudo('chmod 0777 -R {0}'.format(CACHE_PATH))


@task
def deploy():
    """Deploy the project to remote host
    """
    require('stage', provided_by=(production, dev,))

    with cd(env.path):
        permissions()
        run('git checkout {0}'.format(env.branch))
        run('git pull origin {0}'.format(env.branch))
        rmcache()

# ------------------------------------------------------------------------------
# Git
# ------------------------------------------------------------------------------


def commit():
    """GIT Add -> Update -> Commit -> Push
    """
    local('git branch --list')
    branch = prompt('( ) Type a Branch Name: ')
    commit = prompt("( ) Commit Message: ")
    local('git add -u')
    local('git add .')
    local('git commit -m "{0}"'.format(commit))
    local('git push origin {0}'.format(branch))


def pull():
    """Git pull
    """
    branch = prompt('( ) Type a Branch Name: ')
    local('git pull origin {0}'.format(branch))


@task
def compressgit():
    """Flatten Git
    """
    local('rm -rf .git/refs/original/')
    local('git reflog expire --expire=now --all')
    local('git gc --prune=now')
    local('git gc --aggressive --prune=now')
    local('git push origin --force --all')


# ------------------------------------------------------------------------------
# Database
# ------------------------------------------------------------------------------

@task
def dbdump(filename='schema.sql'):
    """Mysql Dump to File
    """
    local("mysqldump -u root jream > %s" % filename)


@task
def dbimport(filename='schema.sql'):
    """Mysql Import from File
    """
    recreate = "DROP DATABASE IF EXISTS jream; \
            CREATE DATABASE IF NOT EXISTS jream;".replace("\n", "")
    local("mysql -u root -h localhost --execute='%s'" % recreate)
    local("mysql -u root -h localhost jream < schema.sql")
