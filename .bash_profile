# Get the aliases and functions
if [ -f ~/.bashrc ]; then
	. ~/.bashrc
fi

#start ssh-agent
if [ -x /bin/bash ]; then
    exec ssh-agent /bin/bash
elif [ -x /usr/bin/bash ]; then
    exec ssh-agent /usr/bin/bash
fi

#HOMEBREW COMPLETION
source `brew --repository`/Library/Contributions/brew_bash_completion.sh

test -e "${HOME}/.iterm2_shell_integration.bash" && source "${HOME}/.iterm2_shell_integration.bash"

# Pyenv setup.
#export PYENV_ROOT="$HOME/.pyenv"
#export PATH="$PYENV_ROOT/bin:$PATH"
#eval "$(pyenv init --path)"
#pyenv init --path | source
