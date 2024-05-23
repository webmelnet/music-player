# music playe
Music Player

## Create bash aliases

_This is not required, but will make your life a lot easier, and is assumed in
the rest of the documentation._

- Go to your root directory. (Path: `~/`).
- Press `CMD + Shift + .` to show hidden files.
- Open your bash file. This will be called either `~/.bashrc` or `.zshrc`
  depending on which terminal you use.
- Add `alias sail="./vendor/bin/sail"` and
  `alias art="./vendor/bin/sail artisan"` to the file.
- Restart your terminal.
- Now you can call those commands as `sail` and `art` in your terminal, instead
  of having to write the full command.
- Example: Use `sail up` to spin up your docker containers instead of
  `./vendor/bin/sail up`.

## Things To Know

### Larave Sail

Laravel Sail is your bridge into the app container. If you run a command and it
doesn't work, try prefixing it with `sail`. For instance, to install a PHP
package you use the `composer` package manager.
`composer require <package-name>` won't work because you are running it on your
local machine. You need to use `sail composer require <package-name>`.

### Front End ( Inertia + Vue + Tailwind )

Vue + Tailwind are already installed. You will find your Vue components in
the `/resources/js` under a typical `Components`, `Layouts`, and `Pages` folder
structure.

Inertia is a package that allows you to use Vue in your application, and it is
doing some magic behind the scenes.
[Inertia - How it works](https://inertiajs.com/how-it-works)  

# Local Environment Installation 

- Clone the repository
- cd {Music-Player-Root-Folder}
- composer install
- npm install
- php artisan key:generate
- copy .env.example to .env -> modify the file to fit to your local server
- ./vendor/bin/sail up -> wait for the build to fisnish
- ./vendor/bin/sail artisan migrate:fresh --seed
- npm run dev
- Open the browser and browse to localhost

# How to connect to mysql
- cd {Music-Player-Root-Folder}
- ./vendor/bin/sail mysql

# Cache