# .bash_profile

# Get the aliases and functions
if [ -f ~/.bashrc ]; then
	. ~/.bashrc
fi

# User specific environment and startup programs

#start ssh-agent
if [ -x /bin/bash ]; then
    exec ssh-agent /bin/bash
elif [ -x /usr/bin/bash ]; then
    exec ssh-agent /usr/bin/bash
fi
