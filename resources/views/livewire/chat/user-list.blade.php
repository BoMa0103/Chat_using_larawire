<div class="users">
    <div class="users-header">
        Users
    </div>

    <ul id="user-list">
    @foreach($users as $user)
            <li wire:click='checkChat({{$user->id}})'>{{$user->name}}</li>
    @endforeach
    </ul>
</div>
