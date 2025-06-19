<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
</head>
<body>
<h1>Edit User</h1>
    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('users.update', $user->id) }}">
       @csrf
        @method('PUT')
        <div>
           <label for="name">Name:</label>
           <input type="text" id="name" name="name" value="{{ $user->name }}" required>
       </div>
        <div>
            <label for="surname">Surname:</label>
             <input type="text" id="surname" name="surname"  value="{{ $user->surname }}" required>
        </div>
        <div>
            <label for="patronymic">Patronymic:</label>
             <input type="text" id="patronymic" name="patronymic"  value="{{ $user->patronymic }}" >
         </div>
         <div>
           <label for="birth_date">Birth Date:</label>
            <input type="date" id="birth_date" name="birth_date" value="{{ $user->birth_date }}">
         </div>
          <div>
            <label for="phone">Phone:</label>
           <input type="text" id="phone" name="phone" value="{{ $user->phone }}" >
        </div>
        <div>
            <label for="start_job_date">Start Job Date:</label>
           <input type="date" id="start_job_date" name="start_job_date" value="{{ $user->start_job_date }}">
        </div>
       <div>
           <label for="email">Email:</label>
           <input type="email" id="email" name="email" value="{{ $user->email }}" required>
      </div>
       <div>
           <label for="role">Role:</label>
            <select name="role" id="role" required>
                <option value="admin" @if($user->role === 'admin') selected @endif>Admin</option>
               <option value="storekeeper" @if($user->role === 'storekeeper') selected @endif>Storekeeper</option>
                <option value="seller" @if($user->role === 'seller') selected @endif>Seller</option>
           </select>
       </div>
        <div>
             <label for="branch_id">Branch ID:</label>
            <input type="number" name="branch_id" id="branch_id"  value="{{ $user->branch_id }}" required>
        </div>
        <div>
            <label for="password">New Password (optional):</label>
            <input type="password" id="password" name="password">
        </div>
        <div>
           <label for="password_confirmation">Confirm new password:</label>
            <input type="password" id="password_confirmation" name="password_confirmation">
        </div>
        <button type="submit">Update User</button>
   </form>
  <form method="POST" action="{{ route('logout') }}">
       @csrf
        <button type="submit">Logout</button>
  </form>
</body>
</html>
