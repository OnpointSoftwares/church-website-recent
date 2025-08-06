// Fetch and render sermons
fetch('sermons_api.php')
  .then(res => res.json())
  .then(data => {
    if (data.sermons && Array.isArray(data.sermons)) {
      const container = document.getElementById('sermons-list');
      if (!container) return;
      container.innerHTML = data.sermons.map(sermon => `
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="sermon-card h-100">
            <div class="sermon-thumbnail">
              <img src="${sermon.thumbnail ? sermon.thumbnail : 'uploads/default_sermon.jpeg'}" alt="Sermon Thumbnail" class="sermon-thumb">
              ${sermon.youtube ? `<a href="${sermon.youtube}" class="youtube-link" target="_blank"><i class="fab fa-youtube"></i></a>` : ''}
            </div>
            <div class="sermon-content">
              <h5 class="mt-3 mb-2">${sermon.title}</h5>
              <div class="sermon-meta mb-2">
                <span><i class="fas fa-calendar-alt me-1"></i> ${sermon.date}</span> &nbsp;|
                <span><i class="fas fa-user me-1"></i> ${sermon.speaker}</span>
              </div>
              <div class="sermon-description">${sermon.content ? sermon.content.substring(0, 110) + (sermon.content.length > 110 ? '...' : '') : ''}</div>
            </div>
          </div>
        </div>
      `).join('');
    }
  })
  .catch(err => {
    const container = document.getElementById('sermons-list');
    if (container) container.innerHTML = '<div class="alert alert-warning">Unable to load sermons at this time.</div>';
  });
