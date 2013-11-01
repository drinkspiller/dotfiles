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

test -r /sw/bin/init.sh && . /sw/bin/init.sh

test -r /sw/bin/init.sh && . /sw/bin/init.sh

#HOMEBREW COMPLETION
source `brew --repository`/Library/Contributions/brew_bash_completion.sh
