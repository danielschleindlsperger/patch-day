// components
import Dashboard from 'pages/ClientDashboard'
import NotFoundPage from 'pages/404'
import Company from 'pages/company/Company'
import AllProjects from 'pages/project/client/Index'
import Project from 'pages/project/client/Project'
import Protocol from 'pages/protocol/client/Protocol'
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