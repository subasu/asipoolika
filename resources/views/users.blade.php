<style>
    table td {padding:10px;}
</style>
<table style="direction: rtl;text-align: center;font-family:'B Yekan';margin-left:400px;" border="1">
    <tr>
        <th>نام</th>
        <th>نام کاربری</th>
        <th>واحد</th>
        <th>سرپرست</th>
    </tr>
    @foreach($users as $user)
        <tr>
            <td>{{$user->name}} {{$user->family}}</td>
            <td>{{$user->username}}</td>
            <td>{{$user->unit_id}}</td>
            <td>@if($user->is_supervisor==1) سرپرست @endif</td>
        </tr>
    @endforeach
</table>
