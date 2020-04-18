"set some junk
set nocompatible
set colorcolumn=80
set autoindent " Copy indent from last line when starting new line.
"set cursorline " Highlight current line
set encoding=utf-8 nobomb " BOM often causes trouble

set esckeys " Allow cursor keys in insert mode.
set expandtab " Expand tabs to spaces
set history=1000 " Increase history from 20 default to 1000
set hlsearch " Highlight searches
set ignorecase " Ignore case of searches.
set incsearch " Highlight dynamically as pattern is typed.
set laststatus=2 " Always show status line
set report=0 " Show all changes.
set ruler " Show the cursor position
set scrolloff=3 " Start scrolling three lines before horizontal border of window.
set shiftwidth=2 " The # of spaces for indenting.
set showmode " Show the current mode.
set sidescrolloff=3 " Start scrolling three columns before vertical border of window.
set smartcase " Ignore 'ignorecase' if search patter contains uppercase characters.
set smarttab " At start of line, <Tab> inserts shiftwidth spaces, <Bs> deletes shiftwidth spaces.
set softtabstop=2 " Tab key results in 2 spaces
set title " Show the filename in the window titlebar.
set ttyfast " Send more characters at a given time.
set ttymouse=xterm " Set mouse type to xterm.

" Local dirs
if version >= 703
    set backupdir=~/.vim/backups
    set directory=~/.vim/swaps
    set undodir=~/.vim/undo
    set undofile " Persistent Undo.
endif

set wildchar=<TAB> " Character for CLI expansion (TAB-completion).
set wildignore+=*.jpg,*.jpeg,*.gif,*.png,*.gif,*.psd,*.o,*.obj,*.min.js
set wildignore+=*/smarty/*,*/vendor/*,*/node_modules/*,*/.git/*,*/.hg/*,*/.svn/*,*/.sass-cache/*,*/log/*,*/tmp/*,*/build/*,*/ckeditor/*
set wildmenu " Hitting TAB in command mode will show possible completions above command line.
set wildmode=list:longest " Complete only until point of ambiguity.
set wrapscan " Searches wrap around end of file

" Remap :W to :w
command WQ wq
command Wq wq
command W w
command Q q

" Yank from cursor to end of line
nnoremap Y y$

syntax on
"colorscheme murphy
colorscheme Tomorrow-Night
set autoindent
set number

"set paste
nnoremap <F2> :set invpaste paste?<CR>
set pastetoggle=<F2>
set notitle
set backspace=indent,eol,start "fix backspace for vim in cygwin

" via http://stackoverflow.com/questions/3785628/format-ruby-code-in-vim
if has("autocmd")
  filetype indent on
endif

autocmd QuickFixCmdPost [^l]* nested cwindow
autocmd QuickFixCmdPost    l* nested lwindow
map <f5> :make %<cr>
let g:user_zen_leader_key = '<c-m>'

" NERDTREE
map <F1> :NERDTreeTabsToggle<CR>
let g:nerdtree_tabs_open_on_console_startup=1
let g:NERDTreeDirArrows=0  "fixes arrows per http://is.gd/dGFXIP
let NERDTreeShowHidden=1

"Enable and disable mouse use
set mouse=nvc

noremap <f3> :call ToggleMouse() <CR>
function! ToggleMouse()
  if &mouse == 'nvc'
    set mouse=
    set nonumber
    NERDTreeTabsClose
    echo "Mouse usage disabled"
  else
    set mouse=nvc
    set number
    NERDTreeTabsOpen
    echo "Mouse usage enabled"
  endif
endfunction


" run pathogen
call pathogen#infect()
call pathogen#runtime_append_all_bundles()
filetype off
syntax on
filetype plugin indent on

set t_Co=256
