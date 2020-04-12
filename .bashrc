##############################
# Mac only aliases
#############################
if [[ $(uname -a) =~ ^Darwin ]]; then
  alias chrome='open -a "Google Chrome"'
  alias iplocal="ipconfig getifaddr en0"
  alias ls='/usr/local/bin/lsd'
  alias ll='ls -alF'
  alias mysql='mysqlsh --sql'
  alias pi='ssh pi@raspberrypi'
  alias pbcopy='xclip -selection clipboard'
  alias pbpaste='xclip -selection clipboard -o'
  alias refreshdns="sudo killall -HUP mDNSResponder"
  alias trash="trash-put"
  alias trashls="trash-list"
fi

##############################
# Linux only aliases
#############################
if [[ ! $(uname -a) =~ ^Darwin ]]; then
  alias del="trash-put"
  alias ll='ls -alhF --color'
  alias open='gio open'
  alias trash="trash-put"
  alias trashls="trash-list"
fi

##############################
# Global aliases
#############################
alias ..="cd .."
alias ...="cd ../../"
alias ....="cd ../../../"
alias ......="cd ../../../../../"
alias .....="cd ../../../../"
# Add an "alert" alias for long running commands.  Use like so:
#   sleep 10; alert
# alias alert='notify-send --urgency=low -i "$([ $? = 0 ] && echo terminal || echo error)" "$(history|tail -n1|sed -e '\''s/^\s*[0-9]\+\s*//;s/[;&|]\s*alert$//'\'')"'
alias delDS='find ./ -name ".DS_Store" -print0 | xargs -0 del -Rf'
alias delMACOSX='find ./ -name "__MACOSX" -print0 | xargs -0 del -Rf'
alias delsvn='echo ">> recursively removing .svn folders from" pwd && del -rf `find . -type d -name .svn`'
alias delThumbsDb='find . -name "*.db" -exec del {} \;'
alias dl="cd ~/Downloads"
alias dt="cd ~/Desktop"
alias egrep='egrep --color=always'
alias fgrep='fgrep --color=always'
alias gwd='cd `cat ~/.cwd`'
alias grep='grep --color=always'
alias howdoi='howdoi -c'
alias less="less -R"
# List a recursive tree.
alias lt='ls -R | grep ":$" | sed -e '"'"'s/:$//'"'"' -e '"'"'s/[^-][^\/]*\//--/g'"'"' -e '"'"'s/^/   /'"'"' -e '"'"'s/-/|    /'"'"''
# Print each PATH entry on a separate line
alias path='echo -e ${PATH//:/\\n}'
alias python='python3'
alias pywatch='watch --silent "**/*.py" -c "python {path}"'
alias reload='. ~/.bashrc'
alias rm="echo WHOOPS, Old habits die hard. Use \'del\', \'trash\' or if rm is actually intended, the full path i.e. '/bin/rm'"
# Enable aliases to be sudo’ed
alias sudo='sudo '
alias vi='/usr/bin/vim'
alias vimi='vim -c start' #start vim in insert mode
alias watch="chokidar"
# Get week number
alias week='date +%V'
alias ydiff='ydiff -s'

# IP addresses
alias ip="curl https://ipinfo.io/ip"
alias ip6="dig @resolver1.opendns.com AAAA myip.opendns.com +short -6"

# You may want to put all your additions into a separate file like
# ~/.bash_aliases, instead of adding them here directly.
# See /usr/share/doc/bash-doc/examples in the bash-doc package.
if [ -f ~/.bash_aliases ]; then
    . ~/.bash_aliases
fi

##############################
# Functions
#############################
function cl_num() {
  p4 -F'%change%' changes -s pending -c "$(p4 -F'%clientName%' info)"
}

function cwd () {
  pwd | tr -d '\n' > ~/.cwd
}

# Create a data URL from a file
function dataurl() {
	local mimeType=$(file -b --mime-type "$1");
	if [[ $mimeType == text/* ]]; then
		mimeType="${mimeType};charset=utf-8";
	fi
	echo "data:${mimeType};base64,$(openssl base64 -in "$1" | tr -d '\n')";
}

function extract() {
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

function functions() {
  grep -Eo '^\s{0,4}(?:function)?\s?([a-zA-Z0-9_]+\(\))' ~/.bashrc | perl -wne '$\ = "\n";/^.*\s([a-zA-Z0-9_]+\(\))/i and print $1'
}

# Used to put notes in a history file
function hnote {
  echo "## NOTE [`date`]: $*" >> $HOME/.history/bash_history-`date +%Y%m%d`
}

# Take current repo and copy it to somewhere else minus the .git stuff.
function gitexport() {
  mkdir -p "$1"
  git archive master | tar -x -C "$1"
}

function gpull() {
  if [ -z "$2" ]; then
    BRANCH=$(git branch | grep "*" | awk '{print $2}')
  else
    BRANCH=$2
  fi

  if [ -z "$1" ]; then
    REMOTE='origin'
  else
    REMOTE=$1
  fi

  CMD="git pull $REMOTE $BRANCH"
  echo $CMD
  $CMD
}

function gpush() {
  if [ -z "$2" ]; then
    BRANCH=$(git branch | grep "*" | awk '{print $2}')
  else
    BRANCH=$2
  fi

  if [ -z "$1" ]; then
    REMOTE='origin'
  else
    REMOTE=$1
  fi

  CMD="git push $REMOTE $BRANCH"
  echo $CMD
  $CMD
}

function historyrange () {
  if [[ -z "$1" ||  -z "$2" ]]
    then
      echo -e "Two parameters are required. Example usage:\nhistoryrange <search term> <# of lines to show before and after match>"
    return
  fi
  history | grep -i -B "$2" -A "$2" "$1"
}

function killport() {
  kill -9 $(lsof -t -i:$1)
}

function largestfiles() {
  if [[ -z "$1" ||  -z "$2" ]]
    then
      echo -e "Two parameters are required. Example usage:\nlargestfiles <start directory> <# of files to list>"
    return
  fi
  find "$1" -type f -exec du -Sh {} + | sort -rh | head -n "$2"
}

function relpath() {
  python -c "import os.path; print os.path.relpath('$1','${2:-$PWD}')" ;
}

# Creates a new directory and enter it.
function md() {
  mkdir -p "$@" && cd "$@"
}

# Starts an HTTP server from a directory, optionally specifying the port.
function server() {
  local port="${1:-8000}"
  open "http://localhost:${port}/"
  # Set the default Content-Type to `text/plain` instead of `application/octet-stream`
  # And serve everything as UTF-8 (although not technically correct, this doesn.t break anything for binary files)
  python -c $'import SimpleHTTPServer;\nmap = SimpleHTTPServer.SimpleHTTPRequestHandler.extensions_map;\nmap[""] = "text/plain";\nfor key, value in map.items():\n\tmap[key] = value + ";charset=UTF-8";\nSimpleHTTPServer.test();' "$port"
}

##############################
# Bash Customization
#############################

# check the window size after each command and, if necessary,
# update the values of LINES and COLUMNS.
shopt -s checkwinsize

# Autocorrect typos in path names when using `cd`
shopt -s cdspell;

# If set, the pattern "**" used in a pathname expansion context will
# match all files and zero or more directories and subdirectories.
#shopt -s globstar

# make less more friendly for non-text input files, see lesspipe(1)
[ -x /usr/bin/lesspipe ] && eval "$(SHELL=/bin/sh lesspipe)"

# Enable programmable completion features (you don't need to enable
# this, if it's already enabled in /etc/bash.bashrc and /etc/profile
# sources /etc/bash.bashrc).
if ! shopt -oq posix; then
  if [ -f /usr/share/bash-completion/bash_completion ]; then
    . /usr/share/bash-completion/bash_completion
  elif [ -f /etc/bash_completion ]; then
    . /etc/bash_completion
  fi
fi

export PATH="/usr/local/git/current/bin/:/usr/local/bin:/usr/bin:/bin:/usr/local/sbin:~/bin:~/bin:~/bin/flutter/bin:$PATH"
export LS_COLORS='no=00:fi=00:di=01;34:ln=01;36:pi=40;33:so=01;35:do=01;35:bd=40;33;01:cd=40;33;01:or=40;31;01:ex=01;32:*.tar=01;31:*.tgz=01;31:*.arj=01;31:*.taz=01;31:*.lzh=01;31:*.zip=01;31:*.z=01;31:*.Z=01;31:*.gz=01;31:*.bz2=01;31:*.deb=01;31:*.rpm=01;31:*.jar=01;31:*.jpg=01;35:*.jpeg=01;35:*.gif=01;35:*.bmp=01;35:*.pbm=01;35:*.pgm=01;35:*.ppm=01;35:*.tga=01;35:*.xbm=01;35:*.xpm=01;35:*.tif=01;35:*.tiff=01;35:*.png=01;35:*.mov=01;35:*.mpg=01;35:*.mpeg=01;35:*.avi=01;35:*.fli=01;35:*.gl=01;35:*.dl=01;35:*.xcf=01;35:*.xwd=01;35:*.ogg=01;35:*.mp3=01;35:*.wav=01;35:'
export PS1='\[\033[36m\]\u\[\033[m\]@\[\033[32m\]\h:\[\033[33;1m\][ \w ]$(__git_ps1 "(%s)")\[\033[m\] $ '
PROMPT_DIRTRIM=3

# Increase History Persistence
# Via https://paste.googleplex.com/4574612784414720
export HISTTIMEFORMAT="%F %r "
# Don't put duplicate lines or lines starting with space in the history.
# See bash(1) for more options
export HISTCONTROL=ignoreboth

# Append to the history file, don't overwrite it.
shopt -s histappend

# Save history after every command; otherwise if your terminal doesn't exit
# cleanly its history is lost.
export PROMPT_COMMAND="history -a"

# pull in command history from other terminals since this terminal was opened
# note: will reset numeric-based history commands
alias hist_reload='history -c; history -r'

# Append to the history file, don't overwrite it.
shopt -s histappend

# For setting history length see HISTSIZE and HISTFILESIZE in bash(1).
export HISTSIZE=100000000
export HISTFILESIZE=200000000

# Log all history forever.
export HISTIGNORE="hnote*"

# Used to keep my history forever.
PROMPT_COMMAND="$PROMPT_COMMAND; [ -d $HOME/.history ] || mkdir -p $HOME/.history; echo : [\$(date)] $$ $USER \$OLDPWD\; \$(history 1 | sed -E 's/^[[:space:]]+[0-9]*[[:space:]]+//g') >> $HOME/.history/bash_history-\`date +%Y%m%d\`"

##############################
# Config
#############################
# Mac
if [[ $(uname -a) =~ ^Darwin ]]; then
  export NVM_DIR="$HOME/.nvm"
  [ -s "$(brew --prefix)/opt/nvm/nvm.sh" ] && . "$(brew --prefix)/opt/nvm/nvm.sh" # This loads nvm
  [ -s "$(brew --prefix)/opt/nvm/etc/bash_completion.d/nvm" ] && . "$(brew --prefix)/opt/nvm/etc/bash_completion.d/nvm" # This loads nvm bash_completion
else
# Linux
  export NVM_DIR="$HOME/.nvm"
  [ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"  # This loads nvm
  [ -s "$NVM_DIR/bash_completion" ] && \. "$NVM_DIR/bash_completion"  # This loads nvm bash_completion
fi

# via https://askubuntu.com/a/540230
alias g='git'
if [ -f ~/.git-completion.bash ]; then
  . ~/.git-completion.bash
fi
complete -o default -o nospace -F _git g

if [ -f ~/.git-prompt.sh ]; then
  . ~/.git-prompt.sh
fi

###-begin-npm-completion-###
#
# npm command completion script
#
# Installation: npm completion >> ~/.bashrc  (or ~/.zshrc)
# Or, maybe: npm completion > /usr/local/etc/bash_completion.d/npm
#

if type complete &>/dev/null; then
  _npm_completion () {
    local words cword
    if type _get_comp_words_by_ref &>/dev/null; then
      _get_comp_words_by_ref -n = -n @ -n : -w words -i cword
    else
      cword="$COMP_CWORD"
      words=("${COMP_WORDS[@]}")
    fi

    local si="$IFS"
    IFS=$'\n' COMPREPLY=($(COMP_CWORD="$cword" \
                           COMP_LINE="$COMP_LINE" \
                           COMP_POINT="$COMP_POINT" \
                           npm completion -- "${words[@]}" \
                           2>/dev/null)) || return $?
    IFS="$si"
    if type __ltrim_colon_completions &>/dev/null; then
      __ltrim_colon_completions "${words[cword]}"
    fi
  }
  complete -o default -F _npm_completion npm
elif type compdef &>/dev/null; then
  _npm_completion() {
    local si=$IFS
    compadd -- $(COMP_CWORD=$((CURRENT-1)) \
                 COMP_LINE=$BUFFER \
                 COMP_POINT=0 \
                 npm completion -- "${words[@]}" \
                 2>/dev/null)
    IFS=$si
  }
  compdef _npm_completion npm
elif type compctl &>/dev/null; then
  _npm_completion () {
    local cword line point words si
    read -Ac words
    read -cn cword
    let cword-=1
    read -l line
    read -ln point
    si="$IFS"
    IFS=$'\n' reply=($(COMP_CWORD="$cword" \
                       COMP_LINE="$line" \
                       COMP_POINT="$point" \
                       npm completion -- "${words[@]}" \
                       2>/dev/null)) || return $?
    IFS="$si"
  }
  compctl -K _npm_completion npm
fi
###-end-npm-completion-###
