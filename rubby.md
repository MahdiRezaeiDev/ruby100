AI Agent Prompt: Rebuild Ruby100 Towing & Car Removal Website
Objective: Rebuild the ruby100.ltd website from scratch based on the existing content, but with a modern, ultra-beautiful design, full responsiveness, and strong SEO optimization for the Australian market. The new site must be secure, fast, and not rely on a vulnerable WordPress installation.

Role & Tone: You are a senior full-stack developer and UI/UX designer. The tone of the website should be professional, trustworthy, and urgent, appealing to customers in need of immediate assistance.

Phase 1: Discovery & Core Setup
Analyze Source Content:

Extract and understand all content from the provided URL (https://ruby100.ltd). This includes:

Hero Section: Tagline "TOWING & CAR REMOVAL SERVICES" and the 24/7 emergency number +61 401 724 002.

"About Company" section text.

"Our Services": Car Removal, Scrap Metal Collection, Towing Services.

"Why Choose Us": List of 6 reasons.

The "Request A Free Quote" form.

Technology Stack Selection:

Choose a static site generator (e.g., Next.js or Astro) for superior performance and security. For a simpler, more robust approach, use HTML, CSS, and Vanilla JavaScript.

CSS Framework: Tailwind CSS for utility-first styling and easy customization.

Form Handling: Use a third-party service like Formspree or Netlify Forms to handle the "Request A Free Quote" form without a backend. This eliminates a major security vulnerability.

Project Structure:

Initialize a new project.

Set up a src directory with folders for components, styles, and pages (if using a framework).

Phase 2: Design & Branding (Ultra-Beautiful & Modern)
Design System:

Color Palette:

Primary: Deep Ruby Red (e.g., #9B111E or #D42020) to represent the brand "Ruby100" and convey power/urgency.

Secondary: Dark Charcoal (e.g., #1E1E1E) and Clean White (#FFFFFF) for contrast and readability.

Accent: A bright, vibrant orange or gold (e.g., #FF8C00) for CTAs (Call to Actions) and highlights.

Typography:

Headings: Use a bold, impactful sans-serif font like Inter or Montserrat.

Body Text: A clean, highly readable font like Open Sans or Roboto.

UI Components:

Implement a sticky, transparent-to-solid navigation bar.

Use subtle, smooth animations (fade-ins, slide-ups) to enhance user experience.

Include a prominent, floating "Call Now" button that is always visible on mobile devices.

Ensure all interactive elements (buttons, links, form inputs) have clear hover and focus states.

Phase 3: Development & Implementation
Build the Pages & Sections:

Single Page Application: For a service business, a single, well-structured landing page is often best. Build the following sections:

Hero Section:

Full-screen background with a high-quality, relevant image (e.g., a tow truck in an urban Australian setting).

Overlay with the main headline, sub-headline, and two prominent CTAs: "Request A Free Quote" and "Call Now: +61 401 724 002".

About Us Section:

Include the "Comprehensive Towing & Car Removal Solutions" text.

Add icons or visuals to break up the text.

Services Section:

Display the 3 services in a grid.

Each service card should have a descriptive icon, title, and a brief summary of the service text provided.

Why Choose Us Section:

Display the 6 reasons in a 3x2 or 2x3 grid.

Use icons (e.g., from FontAwesome or Heroicons) for each reason to make the list visually appealing.

Quote Request Form Section:

Recreate the form.

Ensure the form includes fields for: Name, Phone Number, Email, Service Type (dropdown), and a Message box.

Style it beautifully and connect it to your chosen form handler.

Footer:

Include the 24/7 emergency number, a copyright notice, and a link to a privacy policy.

Security Hardening:

Crucial: Do NOT use WordPress or any PHP-based CMS.

Sanitize and validate form submissions on the client-side and via your form handler.

Add a basic CAPTCHA (e.g., Google reCAPTCHA v3) to the form to prevent bot submissions.

Phase 4: SEO Optimization (Focus on Australia)
On-Page SEO:

Meta Data: Create compelling title and meta description tags.

Title: Ruby100 - #1 Towing & Car Removal Service in Australia | 24/7 Emergency

Description: Get fast, reliable towing and car removal services across Australia. Ruby100 offers 24/7 emergency assistance, instant cash for scrap cars, and eco-friendly recycling. Call +61 401 724 002 now.

Header Tags: Use a single H1 for the main headline, H2 for section titles, and H3 for sub-sections (like service or reason titles).

Image Optimization:

Use descriptive, keyword-rich filenames (e.g., tow-truck-melbourne.jpg).

Set the alt attribute for every image (e.g., "Ruby100 tow truck providing emergency roadside assistance in Sydney").

Use WebP format for images and serve them with responsive sizes.

Structured Data (JSON-LD):

Implement LocalBusiness schema markup.

Include name, description, telephone (+61 401 724 002), areaServed (e.g., "Australia"), and image.

Implement Service and Review schema where appropriate.

URL Structure: Use clean, readable URLs (e.g., ruby100.ltd#services or ruby100.ltd/services).

Mobile-First: Ensure all code is fully responsive, prioritizing the mobile view.

Phase 5: Testing & Deployment
Testing Checklist:

Performance: Run Google PageSpeed Insights. Aim for a score above 90 on mobile and desktop. Fix any render-blocking resources or unoptimized images.

Responsiveness: Test the layout on all screen sizes (desktop, tablet, mobile).

Accessibility: Check color contrast (using WebAIM WCAG guidelines) and ensure keyboard navigation works.

Form: Test form submission thoroughly to ensure you receive emails.

Browser Testing: Test on the latest versions of Chrome, Safari, Firefox, and Edge.

Deployment:

Deploy the final build to a secure, fast hosting provider like Netlify, Vercel, or Cloudflare Pages.

Connect the domain ruby100.ltd to the new host.

Crucial Security Step: Immediately after deploying, delete the old WordPress files from your previous hosting server to ensure the hack is completely removed.

Summary for the AI Agent:
Your final output should be a fully functional, single-page HTML application (or equivalent in your chosen framework) that is visually stunning, lightning-fast, and perfectly tailored for a towing business in Australia. Prioritize a premium user experience, clear calls to action, and a secure foundation that eliminates the possibility of the previous hack recurring.