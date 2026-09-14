const nav = document.getElementById('site-nav')
const menuToggle = document.getElementById('menu-toggle')
const mobileMenu = document.getElementById('mobile-menu')

const solidifyNav = () => {
    if (!nav) return
    if (nav.dataset.alwaysSolid === '1') {
        nav.classList.add('is-solid')
        return
    }
    nav.classList.toggle('is-solid', window.scrollY > 24)
}

solidifyNav()
window.addEventListener('scroll', solidifyNav, { passive: true })

if (menuToggle && mobileMenu) {
    menuToggle.addEventListener('click', () => {
        const open = !mobileMenu.classList.toggle('hidden')
        menuToggle.setAttribute('aria-expanded', String(open))
    })

    mobileMenu.querySelectorAll('[data-close-menu]').forEach((link) => {
        link.addEventListener('click', () => {
            mobileMenu.classList.add('hidden')
            menuToggle.setAttribute('aria-expanded', 'false')
        })
    })
}

const reveals = document.querySelectorAll('.reveal')

if ('IntersectionObserver' in window) {
    const io = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return
                entry.target.classList.add('is-in')
                io.unobserve(entry.target)
            })
        },
        { threshold: 0.14, rootMargin: '0px 0px -48px 0px' },
    )

    reveals.forEach((el) => {
        el.classList.add('reveal-pending')
        io.observe(el)
    })
} else {
    reveals.forEach((el) => el.classList.add('is-in'))
}

document.querySelectorAll('[data-quote-service]').forEach((link) => {
    link.addEventListener('click', (event) => {
        const select = document.getElementById('quote-service')
        if (!select) return
        event.preventDefault()
        select.value = link.dataset.quoteService
        select.dispatchEvent(new Event('change', { bubbles: true }))
        window.location.hash = 'quote'
        select.focus({ preventScroll: true })
    })
})