// components
import Dashboard from 'pages/Dashboard'
import NotFoundPage from 'pages/404'
import Company from 'pages/company/Company'
import AllProjects from 'pages/project/Projects'
import Project from 'pages/project/Project'
import Protocol from 'pages/protocol/Protocol'
// patch days
import PatchDayIndex from 'pages/patch-day/Index'
import PatchDayDetail from 'pages/patch-day/Detail'

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
    path: '/patch-days', component: PatchDayIndex,
  },
  {
    path: '/patch-days/:id', component: PatchDayDetail,
  },
  {
    path: '*', component: NotFoundPage, name: 'not-found'
  },
]