#
#
#
#   todo:
#       - server aufsetzen und verlinken im Intranet
#       - server per ssh key erreichbar
#
#
#


set :application, "BerichtsheftTool"
set :domain,      "192.168.10.47"
set :deploy_to,   "/var/www/projects/berichtshefttool"
set :app_path,    "app"
set :user,        "root"
set(:password) { Capistrano::CLI.ui.ask "Password=> " }
set :repository,  "https://github.com/PalliDotCom/Berichtsheft.git"
set :scm,         :git
# Or: `accurev`, `bzr`, `cvs`, `darcs`, `subversion`, `mercurial`, `perforce`, or `none`
set :deploy_via,    :copy
set :model_manager, "doctrine"
# Or: `propel`

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain                         # This may be the same as your `Web` server
role :db,         domain, :primary => true       # This is where Symfony2 migrations will run

set  :keep_releases,  3

# Be more verbose by uncommenting the following line
logger.level = Logger::MAX_LEVEL

set :shared_files,      ["app/config/parameters.yml"]
set :shared_children,   [app_path + "/logs", web_path + "/uploads", "vendor"]
set :use_composer, true
set :update_vendors, true