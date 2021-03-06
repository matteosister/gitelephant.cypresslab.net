set :application, "GitElephantHost"
set :domain,      "ovh"
set :deploy_to,   "/var/www/ge"
set :app_path,    "app"

set :repository,  "git@github.com:matteosister/gitelephant.cypresslab.net.git"
set :scm,         :git
set :git_enable_submodules, 1
set :branch,      "master"

#set :deploy_via,  :rsync_with_remote_cache

#ssh_options[:port] = "22123"
ssh_options[:forward_agent] = true
ssh_options[:keys] = %w(/home/matteo/.ssh/id_rsa)
set :user, "www-data"

set :model_manager, "doctrine"
# Or: `propel`

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain                         # This may be the same as your `Web` server
role :db,         domain, :primary => true       # This is where Symfony2 migrations will run

set  :use_sudo,      false
set  :keep_releases,  2

set  :dump_assetic_assets, true

set :shared_files,      ["app/config/parameters.yml"]
set :shared_children,   [app_path + "/logs", web_path + "/uploads", "repositories"]
set :update_vendors,    true
set :use_composer, true
set :composer_options,  "--prefer-source --optimize-autoloader --no-progress --no-dev" # --prefer-dist --verbose
set :vendors_mode,      "reinstall"
set :copy_vendors,      true

after "deploy", "deploy:cleanup"
#after "symfony:project:clear_controllers", "cypress:permissions"
before "symfony:assetic:dump", "cypress:bower"
after "cypress:bower", "cypress:compass_compile"

namespace :cypress do
  desc "bower sul server"
  task :bower do
    capifony_pretty_print "--> Installing bower dependencies"
    run "cd #{latest_release} && bower install"

    capifony_puts_ok
  end

  desc "compass compile"
  task :compass_compile do
    capifony_pretty_print "--> Compiling compass"
    run "cd #{latest_release} && #{php_bin} #{symfony_console} --env=#{symfony_env_prod} cypress:compass:compile"

    capifony_puts_ok
  end

  desc "permissions sul server"
  task :permissions do
    capifony_pretty_print "--> resetting permissions"
    run "cd #{latest_release} && chmod 777 app/cache -R"
    run "cd #{latest_release} && chown www-data:www-data . -R"

    capifony_puts_ok
  end
end

# Be more verbose by uncommenting the following line
logger.level = Logger::MAX_LEVEL
