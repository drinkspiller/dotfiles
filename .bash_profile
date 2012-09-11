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

##
# Your previous /Users/sgiordano/.bash_profile file was backed up as /Users/sgiordano/.bash_profile.macports-saved_2012-08-22_at_21:21:49
##

# MacPorts Installer addition on 2012-08-22_at_21:21:49: adding an appropriate PATH variable for use with MacPorts.
export PATH=/opt/local/bin:/opt/local/sbin:$PATH
# Finished adapting your PATH environment variable for use with MacPorts.


##
# Your previous /Users/sgiordano/.bash_profile file was backed up as /Users/sgiordano/.bash_profile.macports-saved_2012-08-22_at_21:25:41
##

# MacPorts Installer addition on 2012-08-22_at_21:25:41: adding an appropriate PATH variable for use with MacPorts.
export PATH=/opt/local/bin:/opt/local/sbin:$PATH
# Finished adapting your PATH environment variable for use with MacPorts.

