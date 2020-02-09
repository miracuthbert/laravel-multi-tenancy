module.exports = {
    title: 'Laravel Multi-Tenancy',
    description: 'A package that provides an easy and flexible way to add single database or multi-database multi-tenancy in your Laravel 5.8 app or above',
    themeConfig: {
        base: '/laravel-multi-tenancy/',
        repo: 'miracuthbert/laravel-multi-tenancy',
        // Customising the header label
        // Defaults to "GitHub"/"GitLab"/"Bitbucket" depending on `themeConfig.repo`
        repoLabel: 'Contribute!',

        // Optional options for generating "Edit this page" link

        // if your docs are in a different repo from your main project:
        docsRepo: 'miracuthbert/laravel-multi-tenancy',
        // if your docs are not at the root of the repo:
        docsDir: 'docs',
        // if your docs are in a specific branch (defaults to 'master'):
        docsBranch: 'master',
        // defaults to false, set to true to enable
        editLinks: true,
        // custom text for edit link. Defaults to "Edit this page"
        editLinkText: 'Help us improve this page!',

        // display header links for all pages
        displayAllHeaders: true,

        // header links
        sidebarDepth: 2,

        sidebar: {
            '/guide/': sideBarLinks()
        },
        nav: [
            { text: 'Guide', link: '/guide/introduction' }
        ]
    }
}

function sideBarLinks() {
    return [
        'introduction',
        'installation',
        {
            'title': 'Setup',
            children: [
                'setup/',
                'setup/tenant',
                'setup/for-tenants'
            ]
        },
        'configuration/',
        'console/',
        'drivers/',
        'usage/'
    ];
}
