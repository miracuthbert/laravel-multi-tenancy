(window.webpackJsonp=window.webpackJsonp||[]).push([[7],{206:function(e,a,t){"use strict";t.r(a);var n=t(0),s=Object(n.a)({},(function(){var e=this,a=e.$createElement,t=e._self._c||a;return t("ContentSlotsDistributor",{attrs:{"slot-key":e.$parent.slotKey}},[t("h1",{attrs:{id:"console"}},[t("a",{staticClass:"header-anchor",attrs:{href:"#console"}},[e._v("#")]),e._v(" Console")]),e._v(" "),t("p",[e._v("There are two sets of commands bundled with the package: "),t("code",[e._v("tenancy")]),e._v(" and "),t("code",[e._v("tenants")])]),e._v(" "),t("h2",{attrs:{id:"tenancy-commands"}},[t("a",{staticClass:"header-anchor",attrs:{href:"#tenancy-commands"}},[e._v("#")]),e._v(" Tenancy Commands")]),e._v(" "),t("p",[e._v("Tenancy commands manage the package by default. They are:")]),e._v(" "),t("ul",[t("li",[t("code",[e._v("tenancy:setup")]),e._v(": Used for setting up the package")]),e._v(" "),t("li",[t("code",[e._v("tenancy:model")]),e._v(": Used for creating models specifically for tenants")]),e._v(" "),t("li",[t("code",[e._v("tenancy:migration")]),e._v(": Used for creating tenant specific migrations")]),e._v(" "),t("li",[t("code",[e._v("tenancy:users")]),e._v(": Used for creating the tenant user migration file")])]),e._v(" "),t("h2",{attrs:{id:"tenants-commands"}},[t("a",{staticClass:"header-anchor",attrs:{href:"#tenants-commands"}},[e._v("#")]),e._v(" Tenants Commands")]),e._v(" "),t("p",[e._v("These are used for setting up a tenant. They simplify most of the work done when a tenant is created.")]),e._v(" "),t("p",[e._v("They handle operations like migrating a tenants database, seed it, rollback migrations and more.")]),e._v(" "),t("p",[e._v("The included commands are:")]),e._v(" "),t("ul",[t("li",[t("code",[e._v("tenants:migrate")]),e._v(": Used to run tenant migrations")]),e._v(" "),t("li",[t("code",[e._v("tenants:seed")]),e._v(": Used to seed tenant databases")]),e._v(" "),t("li",[t("code",[e._v("tenants:rollback")]),e._v(": Rollback tenant migrations")]),e._v(" "),t("li",[t("code",[e._v("tenants:refresh")]),e._v(": Reset and re-run all tenant migrations")]),e._v(" "),t("li",[t("code",[e._v("tenants:reset")]),e._v(": Rollback all database migrations for tenants")])]),e._v(" "),t("p",[e._v("You can define your own tenant based commands and the register them in the "),t("code",[e._v("console")]),e._v(" option of config.")]),e._v(" "),t("p",[e._v("The accepted commands are:")]),e._v(" "),t("h3",{attrs:{id:"migrator"}},[t("a",{staticClass:"header-anchor",attrs:{href:"#migrator"}},[e._v("#")]),e._v(" migrator")]),e._v(" "),t("p",[e._v("These are migration based commands. Basically they deal with the database schema.")]),e._v(" "),t("p",[e._v("The command should accept instances of:")]),e._v(" "),t("ul",[t("li",[t("code",[e._v("Illuminate\\Database\\Migrations\\Migrator")]),e._v(" and")]),e._v(" "),t("li",[t("code",[e._v("Miracuthbert\\Multitenancy\\Database\\TenantDatabaseManager")])])]),e._v(" "),t("h3",{attrs:{id:"db"}},[t("a",{staticClass:"header-anchor",attrs:{href:"#db"}},[e._v("#")]),e._v(" db")]),e._v(" "),t("p",[e._v("These are commands that interact with the database records such as "),t("code",[e._v("tenants:seed")]),e._v(" command.")]),e._v(" "),t("p",[e._v("The command should accept instances:")]),e._v(" "),t("ul",[t("li",[t("code",[e._v("Illuminate\\Database\\ConnectionResolverInterface")]),e._v(" and")]),e._v(" "),t("li",[t("code",[e._v("Miracuthbert\\Multitenancy\\Database\\TenantDatabaseManager")])])])])}),[],!1,null,null,null);a.default=s.exports}}]);