# Working with a doctrine MySQL entities and relations

There are 4 main entities:
1. User
2. Usergroup
3. Task
4. Tasktype

Also one intermediate table user_group for provision _ManyToMany_ relation between users and tasks.

Appropriate tables for each entity are called by _their entity_ name in the _plural_ with a _small letter_.

When log up **user** specifies name of his group and they bind to each other with a bidirectional relation. At home page which is available after authorization each user sees the list of his own tasks.

User can get to the admin page if he has an **admin** role privilege. Administrator can:
1. Create new task type (validation for uniqueness is present).
2. Create new user group (validation for uniqueness is present).
3. Create new tasks (validation for uniqueness is present). And bind them together with an existing task type. Tasks and task types connected bidirectionally like users and user groups do.

4. Bind task and user (validation for uniqueness is present) (The way users get their tasks). Connected also bidirectionally.

When configuring entities in each case admin sees the existing exemplar of target entity and some more info.

Forms are not hardcodded into html but implemented as special objects, processed special way in controllers and displayed special way in templates (see _'src/Form'_, _'src/Controller'_, _'templates'_).

## P.S.
Админитратор может _конфигурировать_ сущности: **создавать** новые и **связывать** существующие. Можно ещё реализовать **удаление** и **редактирование** существующих. Если есть смысл.

Конкретно по этому заданию фундаментальных вопросов нет. Кроме одного: "Можно ли пользоваться репозиторием напрямую в контроллере? Или нужно всегда создавать сервис, к которому обращается контроллер и который обращается к репозиторию? Или это вообще не принципиально? Архитектура всё таки..".