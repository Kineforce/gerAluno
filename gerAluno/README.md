# gerAluno

### Sobre

gerAluno é uma aplicação web, cuja finalidade é ajudar no gerenciamento de alunos em uma instituição de ensino fictícia.

### Tecnologias

Estarei utilizando no desenvolvimento desta aplicação as seguintes tecnologias:

> PHP |
> Codeigniter v3 |
> HTML |
> CSS |
> Javascript |
> JQuery |
> Bootstrap |

### Objetivo

Este projeto foi requisitado na vaga de Analista de TI Junior na empresa IESB.

### Como instalar?

Clone o projeto para seu computador, sob o diretório './gerAluno/application' executar o comando PHP -S localhost:8000

Caso necessite rodar o servidor em uma porta diferente, é possível alterar a variável '$config['base_url']'
no arquivo './application/config/config.php' e definir o novo endereço.

### Dependencias

Projeto rodando nativamente em PHP 7.4, estava tendo problemas de compatibilidade com o SQLITE, então preferi utilizar esta versão.

Devido ao Codeigniter 3 não ter uma forma nativa para lidar com a arquitetura API REST, utilizei uma extensão criada para suprir
essa necessidade, o repositório da dependência se encontra no seguinte link: https://github.com/yidas/codeigniter-rest

Achei a biblioteca bem tranquila de se utilizar, instalar utilizando composer e em seguida, implementar REST_Controller, na 
classe destinada para a API.

No Windows, o Codeigniter 3 não conseguiu reconhecer corretamente o PDO_SQLITE (o mesmo que deve ser habilitado no arquivo php.ini),
a solução que tive foi definir o diretório do PHP nas variáveis de ambiente da seguinte forma: 'C:\php'


