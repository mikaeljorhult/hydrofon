<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class InitCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hydrofon:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create initial user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->ask('Name', 'Hydrofon Administrator');
        $email = $this->ask('E-mail Address');
        $password = $this->secret('Password');

        try {
            $validator = Validator::make(compact(['name', 'email', 'password']), [
                'name' => ['required'],
                'email' => ['required', Rule::unique('users', 'email')],
                'password' => ['required'],
            ]);

            if ($validator->fails()) {
                $this->error('Could not create user!');

                foreach ($validator->errors()->all() as $message) {
                    $this->line($message);
                }

                return 1;
            }

            (new User)->forceFill([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'is_admin' => true,
                'email_verified_at' => now(),
            ])->save();
        } catch (\Exception $e) {
            $this->error('Something went wrong! '.$e->getMessage());

            return 1;
        }

        $this->info('User was created successfully!');

        return 0;
    }
}
