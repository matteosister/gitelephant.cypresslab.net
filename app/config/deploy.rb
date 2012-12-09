set :application, "GitElephantHost"
set :domain,      "cypress"
set :deploy_to,   "/var/www/vhosts/ge.cypresslab.net/httpdocs"
set :app_path,    "app"

set :repository,  "cypressgit:gitelephant_host"
set :scm,         :git
set :git_enable_submodules, 1

set :deploy_via,  :rsync_with_remote_cache

ssh_options[:port] = "22123"

set :model_manager, "doctrine"
# Or: `propel`

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain                         # This may be the same as your `Web` server
role :db,         domain, :primary => true       # This is where Symfony2 migrations will run

set  :keep_releases,  3


set  :dump_assetic_assets, true

set :shared_files,      ["app/config/parameters.yml"]
set :shared_children,   [app_path + "/logs", web_path + "/uploads", "vendor"]
set :update_vendors,    true
set :use_composer, true

after "deploy", "deploy:cleanup"
after "deploy", "cypress:permissions"

namespace :cypress do
  desc "permissions sul server"
  task :permissions do
    run "cd #{latest_release} && chmod 777 app/cache -R"
  end
end

# Be more verbose by uncommenting the following line
# logger.level = Logger::MAX_LEVEL