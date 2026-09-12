(() => {
  'use strict';

  const articles = {
    'weaving-the-future': {
      category: 'Culture',
      title: 'Weaving the Future: The Business of Contemporary African Art',
      author: 'David Osei',
      readTime: '5 min read',
      summary: 'How African artists are building sustainable, globally visible markets.',
      sections: [
        ['A market larger than the moment', 'Contemporary African art is gaining wider attention, but visibility alone does not create a durable market. Artists need strong documentation, fair representation, and clear ways for collectors to understand the value and context of the work.'],
        ['Building creative businesses', 'Sustainable practice depends on more than exhibitions. Pricing, rights management, production planning, and trusted gallery relationships help artists turn recognition into long-term careers.'],
        ['Helping the work travel', 'Digital platforms can widen access, while thoughtful partnerships connect artists with curators, institutions, and buyers. The strongest growth keeps the story and ownership of the work close to its creators.']
      ]
    },
    'powering-progress': {
      category: 'Insights',
      title: 'Powering Progress: The Leapfrog Dynamics of Renewables',
      author: 'The Editorial Board',
      readTime: '12 min read',
      summary: 'The policy and capital expanding off-grid clean energy.',
      sections: [
        ['Why distributed energy matters', 'Renewable systems can reach communities and businesses without waiting for large central grids to expand. Solar, storage, and smaller local networks make energy access more flexible and responsive.'],
        ['Policy and patient capital', 'Clear regulation gives operators and investors the confidence to build. Funding works best when it supports maintenance, local capability, and realistic payment models—not installation alone.'],
        ['From pilots to reliable systems', 'Scale depends on dependable service, trained local teams, and technology suited to each community. Progress is measured by the businesses, schools, and households that can rely on the power every day.']
      ]
    },
    'cross-border-logistics': {
      category: 'Pan-African Stories',
      title: 'In Conversation: Redefining Cross-Border Logistics',
      author: 'Sarah Kone',
      readTime: 'Episode notes',
      summary: 'A practical look at AfCFTA and cross-border growth for small and medium-sized enterprises.',
      sections: [
        ['The opportunity between markets', 'African businesses often see demand beyond their home countries before they have a dependable way to serve it. Better logistics can turn that interest into repeatable regional trade.'],
        ['What smaller businesses need', 'Clear documentation, predictable border processes, transparent pricing, and reliable delivery partners reduce the uncertainty that prevents small firms from expanding.'],
        ['Making AfCFTA practical', 'The agreement creates a shared direction, but everyday progress depends on implementation. Digital customs tools, aligned standards, and collaboration between public and private operators make the opportunity usable.']
      ]
    },
    'payment-rails': {
      category: 'Leadership',
      title: "The Architect Behind West Africa's New Payment Rails",
      author: 'NJC Profiles',
      readTime: '7 min read',
      summary: 'Meet the founders reshaping regional payments.',
      sections: [
        ['Designing for movement', 'Regional commerce needs payment systems that work across currencies, banks, and borders. The challenge is not only speed; it is creating a dependable experience for people and businesses.'],
        ['Trust is part of the product', 'Secure infrastructure, clear settlement processes, and responsive customer support are essential. Growth follows when users understand what will happen to their money at every step.'],
        ['Leadership behind the rails', 'Building financial infrastructure requires patience and coordination. The strongest founders combine technical ambition with regulatory awareness and a close understanding of how customers already transact.']
      ]
    },
    'pivoting-to-profit': {
      category: 'Leadership',
      title: 'Pivoting to Profit: Lessons from a Turnaround CEO',
      author: 'Zainab Bello',
      readTime: '9 min read',
      summary: 'Lessons in strategy, teams, and commercial confidence.',
      sections: [
        ['Clarity before speed', 'A turnaround begins by identifying what creates value and what only creates activity. Clear priorities help leaders stop spreading limited time and capital across too many initiatives.'],
        ['An operating rhythm people can trust', 'Teams perform better when decisions, ownership, and progress are visible. Regular reviews turn strategy into practical work and make problems easier to address early.'],
        ['Confidence built through evidence', 'Commercial confidence returns when customers respond and the numbers improve. Small, measurable wins create the credibility needed for larger changes.']
      ]
    },
    'commercial-hubs': {
      category: 'Insights',
      title: 'Commercial Hubs: Where the Smart Capital Is Flowing',
      author: 'Market Desk',
      readTime: '10 min read',
      summary: 'Where long-term investment is moving across Africa.',
      sections: [
        ['Looking beyond the skyline', 'A successful commercial hub is more than new buildings. Reliable transport, digital infrastructure, skilled talent, and access to customers determine whether a district can support productive businesses.'],
        ['Connected places attract durable value', 'Capital follows locations that make it easier for companies, workers, and suppliers to connect. Mixed-use development and strong public infrastructure can improve that daily experience.'],
        ['A long-term investment lens', 'The most resilient opportunities respond to real economic activity. Investors benefit from studying demand, local partnerships, and operating conditions rather than relying on visibility alone.']
      ]
    },
    'diaspora-partnerships': {
      category: 'Pan-African Stories',
      title: 'Beyond Remittances: A New Era of Diaspora Partnerships',
      author: 'Kofi Mensah',
      readTime: '6 min read',
      summary: 'New models for diaspora-led collaboration.',
      sections: [
        ['More than a transfer of funds', 'Diaspora communities bring knowledge, relationships, market access, and professional experience alongside capital. Structured partnerships can make those strengths useful to growing African businesses.'],
        ['Designing a clear partnership', 'Successful collaboration starts with shared expectations. Defined roles, realistic timelines, local decision-making, and transparent reporting protect trust on both sides.'],
        ['Building networks that last', 'The strongest models connect people around a specific opportunity rather than a broad promise. Repeated collaboration creates the confidence needed for deeper investment and wider impact.']
      ]
    },
    'creative-economy': {
      category: 'Culture',
      title: "The Creative Economy Is Africa's Next Global Language",
      author: 'Culture Desk',
      readTime: '8 min read',
      summary: "How culture is reshaping Africa's global value.",
      sections: [
        ['Culture already travels', 'Music, film, fashion, design, and digital storytelling carry African ideas across borders every day. The next opportunity is to strengthen the businesses and systems behind that attention.'],
        ['Ownership creates lasting value', 'Creators need clear rights, stronger distribution, reliable data, and fair commercial agreements. These foundations help global reach translate into sustainable local value.'],
        ['Collaboration without dilution', 'Partnerships can introduce work to new audiences while preserving its voice. The best collaborators understand that cultural specificity is an advantage, not something to smooth away.']
      ]
    }
  };

  const articleRoot = document.querySelector('[data-article-page]');

  if (!articleRoot) {
    return;
  }

  const slug = new URLSearchParams(window.location.search).get('article');
  const article = articles[slug];

  if (!article) {
    articleRoot.innerHTML = `
      <div class="container article-body article-not-found">
        <h1>Article not found</h1>
        <p>This story may have moved or is no longer available.</p>
        <a class="text-link" href="blog.html">Back to all insights <span class="material-symbols-outlined inline-link-icon" aria-hidden="true">arrow_forward</span></a>
      </div>
    `;
    return;
  }

  document.title = `${article.title} | NJC Global`;
  document.querySelectorAll('[data-article-category]').forEach((element) => {
    element.textContent = article.category;
  });
  document.querySelector('[data-article-title]').textContent = article.title;
  document.querySelector('[data-article-author]').textContent = article.author;
  document.querySelector('[data-article-time]').textContent = article.readTime;
  document.querySelector('[data-article-summary]').textContent = article.summary;
  document.querySelector('[data-article-content]').innerHTML = article.sections.map(([heading, body]) => `
    <section>
      <h2>${heading}</h2>
      <p>${body}</p>
    </section>
  `).join('');
})();
