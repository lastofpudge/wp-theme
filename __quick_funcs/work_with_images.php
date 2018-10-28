{# Arbitrary resizing of images #}
<img src="{{ post.thumbnail.src|resize(300, 200) }}" />
{# Converting images #}
<img src="{{ post.thumbnail.src|tojpg }}" />
