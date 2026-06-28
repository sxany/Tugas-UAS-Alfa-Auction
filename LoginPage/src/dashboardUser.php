<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: /login.html');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Qtalk – Home</title>
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --red:        #E8242A;
    --red-light:  #fff0f0;
    --red-hover:  #c81e23;
    --black:      #111111;
    --gray-900:   #222222;
    --gray-600:   #666666;
    --gray-400:   #999999;
    --gray-200:   #e5e5e5;
    --gray-100:   #f5f5f5;
    --white:      #ffffff;
    --sidebar-w:  240px;
    --nav-h:      68px;
    --radius:     12px;
    --shadow:     0 1px 4px rgba(0,0,0,.08);
  }

  body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    background: var(--gray-100);
    color: var(--black);
    min-height: 100vh;
  }

  /* ── TOPNAV ── */
  .topnav {
    position: fixed; top: 0; left: 0; right: 0; z-index: 100;
    height: var(--nav-h);
    background: var(--white);
    border-bottom: 1px solid var(--gray-200);
    display: flex; align-items: center;
    padding: 0 28px;
    gap: 16px;
  }
  .topnav-logo {
    display: flex; align-items: center; gap: 8px;
    text-decoration: none; flex: 1;
  }
  .logo-icon {
    width: 44px; height: 44px;
    background: var(--red);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; color: var(--white);
    font-weight: 900; position: relative; overflow: hidden;
    box-shadow: 0 2px 8px rgba(232,36,42,.35);
  }
  .logo-icon svg { width: 28px; height: 28px; }
  .logo-text {
    font-size: 22px; font-weight: 800; color: var(--black);
    letter-spacing: -0.5px;
  }
  .logo-text span { color: var(--red); }

  .topnav-actions { display: flex; align-items: center; gap: 18px; }
  .nav-icon-btn {
    position: relative; width: 40px; height: 40px;
    display: flex; align-items: center; justify-content: center;
    border-radius: 50%; border: none; background: none; cursor: pointer;
    color: var(--gray-600); transition: background .15s;
  }
  .nav-icon-btn:hover { background: var(--gray-100); }
  .nav-icon-btn svg { width: 22px; height: 22px; }
  .notif-dot {
    position: absolute; top: 6px; right: 6px;
    width: 8px; height: 8px;
    background: var(--red); border-radius: 50%;
    border: 2px solid var(--white);
  }
  .nav-avatar {
    width: 38px; height: 38px; border-radius: 50%;
    background: var(--gray-200);
    display: flex; align-items: center; justify-content: center;
    overflow: hidden; cursor: pointer;
    border: 2px solid var(--gray-200);
  }
  .nav-avatar svg { width: 22px; height: 22px; color: var(--gray-400); }
  .nav-username { font-size: 14px; font-weight: 600; color: var(--black); white-space: nowrap; }

  /* ── LAYOUT ── */
  .layout {
    display: flex;
    padding-top: var(--nav-h);
    min-height: 100vh;
  }

  /* ── SIDEBAR ── */
  .sidebar {
    width: var(--sidebar-w);
    min-height: calc(100vh - var(--nav-h));
    background: var(--white);
    border-right: 1px solid var(--gray-200);
    position: fixed; top: var(--nav-h); left: 0;
    display: flex; flex-direction: column;
    padding: 24px 16px;
    gap: 4px;
  }
  .nav-item {
    display: flex; align-items: center; gap: 14px;
    padding: 12px 16px; border-radius: var(--radius);
    font-size: 15px; font-weight: 500; color: var(--gray-900);
    cursor: pointer; transition: all .15s; text-decoration: none;
    border: none; background: none; width: 100%; text-align: left;
    position: relative;
  }
  .nav-item:hover { background: var(--gray-100); }
  .nav-item.active { background: var(--red); color: var(--white); font-weight: 600; }
  .nav-item svg { width: 22px; height: 22px; flex-shrink: 0; }
  .nav-item.active svg { color: var(--white); }
  .notif-badge {
    margin-left: auto;
    background: var(--red); color: var(--white);
    font-size: 11px; font-weight: 700;
    border-radius: 50%; width: 20px; height: 20px;
    display: flex; align-items: center; justify-content: center;
  }
  .nav-item.active .notif-badge { background: var(--white); color: var(--red); }

  .sidebar-spacer { flex: 1; }
  .btn-logout {
    display: flex; align-items: center; justify-content: center;
    padding: 12px; border-radius: var(--radius);
    border: 2px solid var(--red); background: none;
    color: var(--red); font-size: 15px; font-weight: 600;
    cursor: pointer; transition: all .15s; width: 100%;
    gap: 8px;
  }
  .btn-logout:hover { background: var(--red-light); }
  .btn-logout svg { width: 18px; height: 18px; }

  /* ── MAIN CONTENT ── */
  .main {
    margin-left: var(--sidebar-w);
    flex: 1;
    display: flex;
    gap: 20px;
    padding: 28px 24px;
    max-width: calc(100vw - var(--sidebar-w));
  }

  .feed-area { flex: 1; min-width: 0; display: flex; flex-direction: column; gap: 20px; }

  .page-header { margin-bottom: 4px; }
  .page-header h1 { font-size: 24px; font-weight: 800; color: var(--black); }
  .page-header p { font-size: 14px; color: var(--gray-600); margin-top: 4px; }

  /* ── STORY / STATUS ROW ── */
  .story-card {
    background: var(--white); border-radius: var(--radius);
    border: 1px solid var(--gray-200);
    padding: 16px; display: flex; gap: 12px;
    box-shadow: var(--shadow); overflow: hidden;
  }
  .story-add {
    width: 120px; height: 160px; flex-shrink: 0;
    border: 2px dashed var(--gray-200); border-radius: 10px;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    cursor: pointer; transition: all .15s;
    gap: 8px; background: var(--gray-100);
    position: relative; overflow: hidden;
  }
  .story-add:hover { border-color: var(--red); background: var(--red-light); }
  .story-add input[type="file"] {
    position: absolute; inset: 0; opacity: 0; cursor: pointer;
    width: 100%; height: 100%;
  }
  .story-add-icon {
    width: 40px; height: 40px; border-radius: 50%;
    background: var(--white); border: 2px solid var(--gray-200);
    display: flex; align-items: center; justify-content: center;
    color: var(--gray-600); pointer-events: none;
  }
  .story-add-icon svg { width: 20px; height: 20px; }
  .story-add span { font-size: 11px; color: var(--gray-600); font-weight: 500; pointer-events: none; text-align: center; }
  .story-add.has-preview img {
    position: absolute; inset: 0; width: 100%; height: 100%;
    object-fit: cover; border-radius: 8px;
  }
  .story-add.has-preview .story-add-icon,
  .story-add.has-preview span { display: none; }

  .story-slots { display: flex; gap: 10px; flex: 1; overflow: hidden; }
  .story-slot {
    width: 120px; height: 160px; flex-shrink: 0;
    border-radius: 10px; background: var(--gray-200);
    display: flex; align-items: center; justify-content: center;
    overflow: hidden;
  }
  .story-slot svg { width: 36px; height: 36px; color: var(--gray-400); }

  /* ── POST CARD ── */
  .post-card {
    background: var(--white); border-radius: var(--radius);
    border: 1px solid var(--gray-200);
    padding: 20px; box-shadow: var(--shadow);
  }
  .post-header { display: flex; align-items: center; gap: 12px; margin-bottom: 14px; }
  .post-avatar {
    width: 46px; height: 46px; border-radius: 50%;
    background: var(--gray-200); flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; overflow: hidden;
  }
  .post-avatar svg { width: 26px; height: 26px; color: var(--gray-400); }
  .post-meta-name { font-size: 15px; font-weight: 700; color: var(--black); }
  .post-meta-tags { font-size: 12px; color: var(--gray-500); margin-top: 1px; }
  .post-body { font-size: 14px; color: var(--gray-900); line-height: 1.55; margin-bottom: 16px; }

  .post-footer {
    display: flex; align-items: center; gap: 4px;
    border-top: 1px solid var(--gray-100); padding-top: 12px;
  }
  .post-action {
    display: flex; align-items: center; gap: 6px;
    padding: 7px 12px; border-radius: 8px;
    border: none; background: none; cursor: pointer;
    color: var(--gray-600); font-size: 13px; font-weight: 500;
    transition: background .15s;
  }
  .post-action:hover { background: var(--gray-100); color: var(--red); }
  .post-action svg { width: 18px; height: 18px; }
  .post-time { margin-left: auto; font-size: 12px; color: var(--gray-400); }

  /* ── RIGHT PANEL ── */
  .right-panel { width: 300px; flex-shrink: 0; display: flex; flex-direction: column; gap: 16px; }

  .panel-card {
    background: var(--white); border-radius: var(--radius);
    border: 1px solid var(--gray-200); padding: 18px;
    box-shadow: var(--shadow);
  }
  .panel-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 14px;
  }
  .panel-title { font-size: 15px; font-weight: 700; color: var(--black); }
  .panel-link { font-size: 13px; font-weight: 600; color: var(--red); cursor: pointer; }
  .panel-link:hover { text-decoration: underline; }

  .person-row {
    display: flex; align-items: center; gap: 10px;
    padding: 8px 0; border-bottom: 1px solid var(--gray-100);
  }
  .person-row:last-child { border-bottom: none; padding-bottom: 0; }
  .person-avatar {
    width: 40px; height: 40px; border-radius: 50%;
    background: var(--gray-200); flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; overflow: hidden;
  }
  .person-avatar svg { width: 22px; height: 22px; color: var(--gray-400); }
  .person-info { flex: 1; min-width: 0; }
  .person-name { font-size: 13px; font-weight: 600; color: var(--black); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .person-tags { font-size: 11px; color: var(--gray-400); }
  .btn-add-friend {
    width: 30px; height: 30px; border-radius: 50%;
    border: none; background: none; cursor: pointer;
    color: var(--gray-500); display: flex; align-items: center; justify-content: center;
    transition: all .15s;
  }
  .btn-add-friend:hover { background: var(--red-light); color: var(--red); }
  .btn-add-friend svg { width: 18px; height: 18px; }

  .online-row {
    display: flex; align-items: center; gap: 10px;
    padding: 8px 0; border-bottom: 1px solid var(--gray-100);
  }
  .online-row:last-child { border-bottom: none; }
  .online-avatar-wrap { position: relative; }
  .online-avatar {
    width: 40px; height: 40px; border-radius: 50%;
    background: var(--gray-200); flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; overflow: hidden;
  }
  .online-avatar svg { width: 22px; height: 22px; color: var(--gray-400); }
  .online-dot {
    position: absolute; bottom: 1px; right: 1px;
    width: 11px; height: 11px; background: #22c55e;
    border-radius: 50%; border: 2px solid var(--white);
  }
</style>
</head>
<body>

<!-- ── TOP NAV ── -->
<nav class="topnav">
  <a class="topnav-logo" href="#">
    <div class="logo-icon">
      <svg viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
        <circle cx="14" cy="14" r="12" fill="white" opacity="0.15"/>
        <path d="M7 10c0-2.2 1.8-4 4-4h6c2.2 0 4 1.8 4 4v4c0 2.2-1.8 4-4 4h-2l-4 3v-3H11c-2.2 0-4-1.8-4-4v-4z" fill="white"/>
        <circle cx="11" cy="12" r="1.5" fill="#E8242A"/>
        <circle cx="17" cy="12" r="1.5" fill="#E8242A"/>
      </svg>
    </div>
    <span class="logo-text">Q<span>talk</span></span>
  </a>

  <div class="topnav-actions">
    <!-- Notification -->
    <button class="nav-icon-btn">
      <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
        <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-5-5.917V4a1 1 0 10-2 0v1.083A6 6 0 006 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
      </svg>
      <span class="notif-dot"></span>
    </button>
    <!-- Messages -->
    <button class="nav-icon-btn">
      <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
        <path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
      </svg>
    </button>
    <!-- Avatar -->
    <div class="nav-avatar">
      <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zm-4 7a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
      </svg>
    </div>
    <span class="nav-username">Ementerio</span>
  </div>
</nav>

<!-- ── LAYOUT ── -->
<div class="layout">

  <!-- ── SIDEBAR ── -->
  <aside class="sidebar">
    <a class="nav-item active" href="#">
      <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
      </svg>
      Home
    </a>

    <a class="nav-item" href="#">
      <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
      </svg>
      Search Friend
    </a>

    <a class="nav-item" href="#">
      <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-5-5.917V4a1 1 0 10-2 0v1.083A6 6 0 006 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
      </svg>
      Notification
      <span class="notif-badge">5</span>
    </a>

    <a class="nav-item" href="#">
      <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
      </svg>
      Messages
    </a>

    <a class="nav-item" href="#">
      <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/>
      </svg>
      Settings
    </a>

    <a class="nav-item" href="#">
      <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3m.08 4h.01"/>
      </svg>
      Help
    </a>

    <div class="sidebar-spacer"></div>

    <button class="btn-logout">
      <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
      </svg>
      Logout
    </button>
  </aside>

  <!-- ── MAIN ── -->
  <main class="main">
    <div class="feed-area">

      <!-- Page Header -->
      <div class="page-header">
        <h1>Welcome Back, Ementerio!</h1>
        <p>Find new friends and connect with amazing people.</p>
      </div>

      <!-- Story / Status Row -->
      <div class="story-card">
        <!-- Add Story Button -->
        <div class="story-add" id="storyAdd">
          <input type="file" accept="image/*,video/*" id="storyFile" onchange="previewStory(event)" />
          <div class="story-add-icon">
            <svg fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
              <path d="M12 4v16m8-8H4"/>
            </svg>
          </div>
          <span>Add Story</span>
        </div>

        <!-- Empty Story Slots -->
        <div class="story-slots">
          <div class="story-slot">
            <svg fill="none" stroke="currentColor" stroke-width="1.3" viewBox="0 0 24 24">
              <rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/>
            </svg>
          </div>
          <div class="story-slot">
            <svg fill="none" stroke="currentColor" stroke-width="1.3" viewBox="0 0 24 24">
              <rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/>
            </svg>
          </div>
          <div class="story-slot">
            <svg fill="none" stroke="currentColor" stroke-width="1.3" viewBox="0 0 24 24">
              <rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/>
            </svg>
          </div>
          <div class="story-slot">
            <svg fill="none" stroke="currentColor" stroke-width="1.3" viewBox="0 0 24 24">
              <rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/>
            </svg>
          </div>
        </div>
      </div>

      <!-- Post Card 1 -->
      <div class="post-card">
        <div class="post-header">
          <div class="post-avatar">
            <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
              <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zm-4 7a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
          </div>
          <div>
            <div class="post-meta-name">Beckam Alfred</div>
            <div class="post-meta-tags">Music, Reading, Tech</div>
          </div>
        </div>
        <p class="post-body">Just finished some novel and can't move on</p>
        <div class="post-footer">
          <button class="post-action">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
            Like
          </button>
          <button class="post-action">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            Comment
          </button>
          <button class="post-action">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            Add Friend
          </button>
          <span class="post-time">2 hours ago</span>
        </div>
      </div>

      <!-- Post Card 2 -->
      <div class="post-card">
        <div class="post-header">
          <div class="post-avatar">
            <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
              <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zm-4 7a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
          </div>
          <div>
            <div class="post-meta-name">Pak Kris</div>
            <div class="post-meta-tags">Food, Wirausaha, Film</div>
          </div>
        </div>
        <p class="post-body">Cumi hideung mangga pesen ka kuring</p>
        <div class="post-footer">
          <button class="post-action">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
            Like
          </button>
          <button class="post-action">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            Comment
          </button>
          <button class="post-action">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            Add Friend
          </button>
          <span class="post-time">3 hours ago</span>
        </div>
      </div>

    </div><!-- /feed-area -->

    <!-- ── RIGHT PANEL ── -->
    <aside class="right-panel">

      <!-- People You May Know -->
      <div class="panel-card">
        <div class="panel-header">
          <span class="panel-title">People You May Know</span>
          <span class="panel-link">See All</span>
        </div>

        <!-- Person Row -->
        <div class="person-row">
          <div class="person-avatar">
            <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
              <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zm-4 7a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
          </div>
          <div class="person-info">
            <div class="person-name">Beckam Alfred</div>
            <div class="person-tags">Music, Reading, Tech</div>
          </div>
          <button class="btn-add-friend">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
          </button>
        </div>

        <div class="person-row">
          <div class="person-avatar">
            <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
              <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zm-4 7a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
          </div>
          <div class="person-info">
            <div class="person-name">Rina Marlina</div>
            <div class="person-tags">Travel, Cooking, Art</div>
          </div>
          <button class="btn-add-friend">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
          </button>
        </div>

        <div class="person-row">
          <div class="person-avatar">
            <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
              <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zm-4 7a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
          </div>
          <div class="person-info">
            <div class="person-name">Dimas Pratama</div>
            <div class="person-tags">Gaming, Music, Tech</div>
          </div>
          <button class="btn-add-friend">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
          </button>
        </div>

        <div class="person-row">
          <div class="person-avatar">
            <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
              <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zm-4 7a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
          </div>
          <div class="person-info">
            <div class="person-name">Sari Dewi</div>
            <div class="person-tags">Film, Reading, Yoga</div>
          </div>
          <button class="btn-add-friend">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
          </button>
        </div>
      </div>

      <!-- People Online -->
      <div class="panel-card">
        <div class="panel-header">
          <span class="panel-title">People Online</span>
          <span class="panel-link">View All</span>
        </div>

        <div class="online-row">
          <div class="online-avatar-wrap">
            <div class="online-avatar">
              <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zm-4 7a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
            </div>
            <span class="online-dot"></span>
          </div>
          <div class="person-info">
            <div class="person-name">Pak Kris</div>
            <div class="person-tags">Food, Wirausaha, Film</div>
          </div>
        </div>

        <div class="online-row">
          <div class="online-avatar-wrap">
            <div class="online-avatar">
              <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zm-4 7a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
            </div>
            <span class="online-dot"></span>
          </div>
          <div class="person-info">
            <div class="person-name">Ani Susanti</div>
            <div class="person-tags">Fashion, Art, Travel</div>
          </div>
        </div>

        <div class="online-row">
          <div class="online-avatar-wrap">
            <div class="online-avatar">
              <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zm-4 7a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
            </div>
            <span class="online-dot"></span>
          </div>
          <div class="person-info">
            <div class="person-name">Budi Santoso</div>
            <div class="person-tags">Gaming, Tech, Food</div>
          </div>
        </div>

      </div>
    </aside>
  </main>
</div>

<script>
  function previewStory(event) {
    const file = event.target.files[0];
    if (!file) return;
    const wrap = document.getElementById('storyAdd');
    // Remove old preview img if any
    const old = wrap.querySelector('img.preview-img');
    if (old) old.remove();
    const reader = new FileReader();
    reader.onload = (e) => {
      const img = document.createElement('img');
      img.src = e.target.result;
      img.classList.add('preview-img');
      img.style.cssText = 'position:absolute;inset:0;width:100%;height:100%;object-fit:cover;border-radius:8px;pointer-events:none;';
      wrap.appendChild(img);
      wrap.classList.add('has-preview');
    };
    reader.readAsDataURL(file);
  }
</script>

</body>
</html>
