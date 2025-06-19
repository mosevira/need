<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
</head>
<body>
<h1>Users</h1>

    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

     <a href="{{ route('users.create') }}">Создать пользователя</a>

    <table border="1">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Branch</th>
            <th>Actions</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
             <tr>
                 <td>{{$user->id}}</td>
                 <td>{{$user->name}}</td>
                 <td>{{$user->email}}</td>
                 <td>{{$user->role}}</td>
                 <td>{{$user->branch->name ?? 'Not set'}}</td>
                 <td>
                     <a href="{{ route('users.edit', $user->id) }}">Edit</a>
                     <form method="post" action="{{ route('users.destroy', $user->id) }}" style="display: inline">
                         @csrf
                         @method('DELETE')
                         <button type="submit" onclick="return confirm('Are you sure?')">Delete</button>
                     </form>
                     @if($user->is_active)
                         <form method="post" action="{{ route('users.deactivate', $user->id) }}" style="display: inline">
                             @csrf
                             <button type="submit">Deactivate</button>
                         </form>
                      @else
                         <form method="post" action="{{ route('users.activate', $user->id) }}" style="display: inline">
                              @csrf
                              <button type="submit">Activate</button>
                          </form>
                     @endif

                 </td>
                 <td>
                     @if($user->is_active)
                         Active
                     @else
                          Inactive
                      @endif
                </td>
            </tr>
         @endforeach
        </tbody>
    </table>
  <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
   </form>
</body>
</html>
