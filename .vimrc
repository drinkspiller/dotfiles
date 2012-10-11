"set some junk
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
set mouse=nvc
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

"#####################################
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

" NERDTREE
"map <F1> :NERDTreeToggle<CR>
map <F1> :NERDTreeTabsToggle<CR>
let g:nerdtree_tabs_open_on_console_startup=1
let g:NERDTreeDirArrows=0  "fixes arrows per http://is.gd/dGFXIP
"close vim if the only window left open is a NERDTree:
"autocmd bufenter * if (winnr("$") == 1 && exists("b:NERDTreeType") && b:NERDTreeType == "primary") | q | endif
"open a NERDTree automatically when vim starts up if no files were specified:
"autocmd vimenter * if !argc() | NERDTree | endif




" run pathogen
call pathogen#infect()
set t_Co=256
