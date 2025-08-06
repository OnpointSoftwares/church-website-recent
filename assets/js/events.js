// Fetch and render upcoming events
fetch('admin/events_api.php')
  .then(res => res.json())
  .then(data => {
    if (data.events && Array.isArray(data.events)) {
      const container = document.getElementById('events-list');
      if (!container) return;
      container.innerHTML = data.events.map(event => `
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="event-card h-100">
            <div class="event-banner" style="background-image:url('${event.banner ? event.banner : 'assets/images/default-event.jpg'}')">
              <div class="event-date">
                <span class="event-day">${new Date(event.date).getDate()}</span>
                <span class="event-month">${new Date(event.date).toLocaleString('default', { month: 'short' })}</span>
              </div>
            </div>
            <div class="event-content">
              <h5 class="mt-3 mb-2">${event.title}</h5>
              <div class="event-meta mb-2">
                <span><i class="fas fa-calendar-alt me-1"></i> ${event.date}</span> &nbsp;|
                <span><i class="fas fa-clock me-1"></i> ${event.time}</span> &nbsp;|
                <span><i class="fas fa-map-marker-alt me-1"></i> ${event.location}</span>
              </div>
              <div class="event-description">${event.description ? event.description.substring(0, 90) + (event.description.length > 90 ? '...' : '') : ''}</div>
            </div>
          </div>
        </div>
      `).join('');
    }
  })
  .catch(err => {
    const container = document.getElementById('events-list');
    if (container) container.innerHTML = '<div class="alert alert-warning">Unable to load events at this time.</div>';
  });
