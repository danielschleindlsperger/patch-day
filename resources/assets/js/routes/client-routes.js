// components
import Dashboard from 'pages/ClientDashboard'
import NotFoundPage from 'pages/404'
import Company from 'pages/company/Company'
import AllProjects from 'pages/project/client/Index'
import Project from 'pages/project/client/Project'
import Protocol from 'pages/protocol/client/Protocol'

export default [
  {
    path: '/', component: Dashboard,
  },
  {
    path: '/companies/:id', component: Company
  },
  {
    path: '/projects', component: AllProjects,
  },
  {
    path: '/projects/:id', component: Project,
  },
  {
    path: '/protocols/:id', component: Protocol,
  },
  {
    path: '*', component: NotFoundPage, name: 'not-found'
  },
]