<h2>💿</h2>
<blockquote>
    @foreach ($announcements as $announcement)
        <p style="{{ ! $loop->first ? 'text-decoration: line-through' : '' }}">{{ $announcement }}</p>
    @endforeach
</blockquote>
