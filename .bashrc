# .bashrc

# Source global definitions
if [ -f /etc/bashrc ]; then
	. /etc/bashrc
fi

##############################
# ALIASES
#############################
# Easier navigation: .., ..., ~ and -
alias ..="cd .."
alias ...="cd ../.."
alias ll='ls -al'


# List only directories
alias lsd='ls -l | grep "^d"'

# Always use color output for `ls`
if [[ "$OSTYPE" =~ ^darwin ]]; then
    alias ls="command ls -G"
else
    alias ls="command ls --color"
    export LS_COLORS='no=00:fi=00:di=01;34:ln=01;36:pi=40;33:so=01;35:do=01;35:bd=40;33;01:cd=40;33;01:or=40;31;01:ex=01;32:*.tar=01;31:*.tgz=01;31:*.arj=01;31:*.taz=01;31:*.lzh=01;31:*.zip=01;31:*.z=01;31:*.Z=01;31:*.gz=01;31:*.bz2=01;31:*.deb=01;31:*.rpm=01;31:*.jar=01;31:*.jpg=01;35:*.jpeg=01;35:*.gif=01;35:*.bmp=01;35:*.pbm=01;35:*.pgm=01;35:*.ppm=01;35:*.tga=01;35:*.xbm=01;35:*.xpm=01;35:*.tif=01;35:*.tiff=01;35:*.png=01;35:*.mov=01;35:*.mpg=01;35:*.mpeg=01;35:*.avi=01;35:*.fli=01;35:*.gl=01;35:*.dl=01;35:*.xcf=01;35:*.xwd=01;35:*.ogg=01;35:*.mp3=01;35:*.wav=01;35:'
fi


#RUBY SHORTCUTS
alias routes='vim ./config/routes.rb'

# GIT STUFF
# Undo a `git push`
alias undopush="git push -f origin HEAD^:master"


alias reload='. ~/.bashrc'
alias vi='/usr/bin/vim'

#per platform hosts alias
if [ -f /cygdrive/c/Windows/System32/drivers/etc/hosts ]; then
    alias hosts='vim /cygdrive/c/Windows/System32/drivers/etc/hosts'
elif [ -f /private/etc/hosts ]; then
    alias hosts='vim /private/etc/hosts'
elif [ -f /etc/hosts ]; then
    alias hosts='vim /etc/hosts'
fi

alias Desktop='cd /cygdrive/c/Users/sgiordano/Desktop'
alias desktop='cd /cygdrive/c/Users/sgiordano/Desktop'
alias delsvn='find ./ -name ".svn" -0 | xargs -0 rm -Rf'
alias delDS='find ./ -name ".DS_Store" -print0 | xargs -0 rm -Rf'
alias delMACOSX='find ./ -name "__MACOSX" -print0 | xargs -0 rm -Rf'

# IP addresses
alias ip="dig +short myip.opendns.com @resolver1.opendns.com"
alias localip="ipconfig getifaddr en1"
alias ips="ifconfig -a | perl -nle'/(\d+\.\d+\.\d+\.\d+)/ && print $1'"


##############################
# PATH
#############################
PATH=$PATH:$HOME/.rvm/bin # Add RVM to PATH for scripting
PATH="/usr/local/bin:/usr/bin:/bin:$PATH"
export PATH


##############################
# CONFIG
#############################
#RVM:
[[ -s "$HOME/.rvm/scripts/rvm" ]] && source "$HOME/.rvm/scripts/rvm" # Load RVM into a shell session *as a function*

#prompt:
export PS1="\[\033[36m\]\u\[\033[m\]@\[\033[32m\]\h:\[\033[33;1m\][ \w ]\[\033[m\] $ "

##############################
# FUNCTIONS
#############################
# Create a new directory and enter it
function md() {
    mkdir -p "$@" && cd "$@"
}


# find shorthand
function f() {
    find . -name "$1"
}

# Start an HTTP server from a directory, optionally specifying the port
function server() {
    local port="${1:-8000}"
    open "http://localhost:${port}/"
    # Set the default Content-Type to `text/plain` instead of `application/octet-stream`
    # And serve everything as UTF-8 (although not technically correct, this doesn.t break anything for binary files)
    python -c $'import SimpleHTTPServer;\nmap = SimpleHTTPServer.SimpleHTTPRequestHandler.extensions_map;\nmap[""] = "text/plain";\nfor key, value in map.items():\n\tmap[key] = value + ";charset=UTF-8";\nSimpleHTTPServer.test();' "$port"
}

# take this repo and copy it to somewhere else minus the .git stuff.
function gitexport(){
    mkdir -p "$1"
    git archive master | tar -x -C "$1"
}


function extract()      # Handy Extract Program.
{
     if [ -f $1 ] ; then
         case $1 in
             *.tar.bz2)   tar xvjf $1     ;;
             *.tar.gz)    tar xvzf $1     ;;
             *.bz2)       bunzip2 $1      ;;
             *.rar)       unrar x $1      ;;
             *.gz)        gunzip $1       ;;
             *.tar)       tar xvf $1      ;;
             *.tbz2)      tar xvjf $1     ;;
             *.tgz)       tar xvzf $1     ;;
             *.zip)       unzip $1        ;;
             *.Z)         uncompress $1   ;;
             *.7z)        7z x $1         ;;
             *)           echo "'$1' cannot be extracted via >extract<" ;;
         esac
     else
         echo "'$1' is not a valid file"
     fi
}
