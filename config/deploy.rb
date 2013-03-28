set :stages, %w(production development)

require 'capistrano/ext/multistage'
require 'bundler/capistrano'
require 'capistrano_colors'

set :use_sudo, true
set :user, 'ec2-user'
set :scm, :svn
set :repository, 'git@bitbucket.org:drinkspiller/cbdam.git'
set :deploy_to, "/home/deploy/media.cannonballagency.com"
set :deploy_via, :remote_cache
set :use_sudo, false

#ssh_options[:forward_agent] = true
default_run_options[:pty] = true

set :ssh_options, {:auth_methods => "publickey"}
set :ssh_options, {:keys => ["/Users/sgiordano/.ssh/cannonballweb.pem"]}

role :web, "media.cannonballagency.com"                          # Your HTTP server, Apache/etc
role :app, "media.cannonballagency.com"                          # This may be the same as your `Web` server
role :db,  "media.cannonballagency.com", :primary => true # This is where Rails migrations will run
role :db,  "media.cannonballagency.com"