<?php

namespace App\Logic;

use App\Models\User;

class UserLogic
{
    /**
     * register new user to the database
     *
     * @param string $name
     * @param string $password
     * @param string $username
     * @param string $image_path
     *
     * @return User CreatedUSer
     */
	public function insert(string $username, string $email, string $password, string $image_path = null) {
        $newUser = new User;
        $newUser->name = $username;
        $newUser->email = $email;
        $newUser->password = bcrypt($password);
        $newUser->image_path = $image_path;
        $newUser->save();
        return $newUser;
	}

    public function getInitials(string $user_name)
    {
        $full_name = strtoupper($user_name);
        $initials = '';
        $name_array = explode(' ', $full_name);
        foreach ($name_array as $name) {
            $initials .= substr($name, 0, 1);
        }

        if (strlen($initials) >= 2) {
            $initials = substr($initials, 0, 2);
        }
        return $initials;
    }
}
