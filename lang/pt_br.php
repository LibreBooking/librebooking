<?php

require_once('Language.php');
require_once('en_gb.php');

class pt_br extends en_gb
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return array
     */
    protected function _LoadStrings()
    {
        $strings = parent::_LoadStrings();

        $strings['FirstName'] = 'Nome';
        $strings['LastName'] = 'Sobrenome';
        $strings['Timezone'] = 'Fuso Horário';
        $strings['Edit'] = 'Editar';
        $strings['Change'] = 'Alterar';
        $strings['Rename'] = 'Renomear';
        $strings['Remove'] = 'Remover';
        $strings['Delete'] = 'Excluir';
        $strings['Update'] = 'Atualizar';
        $strings['Cancel'] = 'Cancelar';
        $strings['Add'] = 'Adicionar';
        $strings['Name'] = 'Nome';
        $strings['Yes'] = 'Sim';
        $strings['No'] = 'Não';
        $strings['FirstNameRequired'] = 'Nome é obrigatório.';
        $strings['LastNameRequired'] = 'Sobrenome é obrigatório.';
        $strings['PwMustMatch'] = 'A confirmação da senha deve corresponder à senha.';
        $strings['ValidEmailRequired'] = 'Um endereço de e-mail válido é obrigatório.';
        $strings['UniqueEmailRequired'] = 'Esse endereço de e-mail já está registrado.';
        $strings['UniqueUsernameRequired'] = 'Esse nome de usuário já está registrado.';
        $strings['UserNameRequired'] = 'Nome de usuário é obrigatório.';
        $strings['CaptchaMustMatch'] = 'Por favor digite as letras da imagem de segurança exatamente como mostrado.';
        $strings['Today'] = 'Hoje';
        $strings['Week'] = 'Semana';
        $strings['Month'] = 'Mês';
        $strings['BackToCalendar'] = 'Voltar ao calendário';
        $strings['BeginDate'] = 'Início';
        $strings['EndDate'] = 'Fim';
        $strings['Username'] = 'Usuário';
        $strings['Password'] = 'Senha';
        $strings['PasswordConfirmation'] = 'Confirmar Senha';
        $strings['DefaultPage'] = 'Página Inicial Padrão';
        $strings['MyCalendar'] = 'Meu Calendário';
        $strings['ScheduleCalendar'] = 'Calendário de Agendas';
        $strings['Registration'] = 'Registro';
        $strings['NoAnnouncements'] = 'Não há anúncios';
        $strings['Announcements'] = 'Anúncios';
        $strings['NoUpcomingReservations'] = 'Você não tem reservas futuras';
        $strings['UpcomingReservations'] = 'Reservas futuras';
        $strings['AllNoUpcomingReservations'] = 'Não há reservas futuras nos próximos %s dias';
        $strings['AllUpcomingReservations'] = 'Todas as reservas futuras';
        $strings['ShowHide'] = 'Mostrar/Ocultar';
        $strings['Error'] = 'Erro';
        $strings['ReturnToPreviousPage'] = 'Retornar à última página em que você estava';
        $strings['UnknownError'] = 'Erro Desconhecido';
        $strings['InsufficientPermissionsError'] = 'Você não tem permissão para acessar este recurso';
        $strings['MissingReservationResourceError'] = 'Um recurso não foi selecionado';
        $strings['MissingReservationScheduleError'] = 'A agenda não foi selecionada';
        $strings['DoesNotRepeat'] = 'Não repetir';
        $strings['Daily'] = 'Diariamente';
        $strings['Weekly'] = 'Semanalmente';
        $strings['Monthly'] = 'Mensalmente';
        $strings['Yearly'] = 'Anualmente';
        $strings['RepeatPrompt'] = 'Repetir';
        $strings['minutes'] = 'minutos';
        $strings['hours'] = 'horas';
        $strings['days'] = 'dias';
        $strings['weeks'] = 'semanas';
        $strings['months'] = 'meses';
        $strings['years'] = 'anos';
        $strings['day'] = 'dia';
        $strings['week'] = 'semana';
        $strings['month'] = 'mês';
        $strings['year'] = 'ano';
        $strings['repeatDayOfMonth'] = 'dia do mês';
        $strings['repeatDayOfWeek'] = 'dia da semana';
        $strings['RepeatUntilPrompt'] = 'Até';
        $strings['RepeatEveryPrompt'] = 'A cada';
        $strings['RepeatDaysPrompt'] = 'Em';
        $strings['CreateReservationHeading'] = 'Nova Reserva';
        $strings['EditReservationHeading'] = 'Edição da Reserva %s';
        $strings['ViewReservationHeading'] = 'Visualização reserva %s';
        $strings['ReservationErrors'] = 'Alterar Reserva';
        $strings['Create'] = 'Criar';
        $strings['ThisInstance'] = 'Somente esta instância';
        $strings['AllInstances'] = 'Todas as instâncias';
        $strings['FutureInstances'] = 'Instâncias futuras';
        $strings['Print'] = 'Imprimir';
        $strings['ShowHideNavigation'] = 'Mostrar/Ocultar Navegação';
        $strings['ReferenceNumber'] = 'Número de Referência';
        $strings['Tomorrow'] = 'Amanhã';
        $strings['LaterThisWeek'] = 'Ainda esta semana';
        $strings['NextWeek'] = 'Próxima Semana';
        $strings['SignOut'] = 'Sair';
        $strings['LayoutDescription'] = 'Inicia em %s, mostrando %s dias de cada vez';
        $strings['AllResources'] = 'Todos os Recursos';
        $strings['TakeOffline'] = 'Colocar Offline';
        $strings['BringOnline'] = 'Colocar Online';
        $strings['AddImage'] = 'Adicionar Imagem';
        $strings['NoImage'] = 'Nenhuma Imagem Atribuída';
        $strings['Move'] = 'Mover';
        $strings['AppearsOn'] = 'Aparece Em %s';
        $strings['Location'] = 'Local';
        $strings['NoLocationLabel'] = '(Nenhum local definido)';
        $strings['Contact'] = 'Contato';
        $strings['NoContactLabel'] = '(Nenhuma informação de contato)';
        $strings['Description'] = 'Descrição';
        $strings['NoDescriptionLabel'] = '(Nenhuma descrição)';
        $strings['Notes'] = 'Notas';
        $strings['NoNotesLabel'] = '(Nenhuma nota)';
        $strings['NoTitleLabel'] = '(Nenhum título)';
        $strings['UsageConfiguration'] = 'Configuração de Uso';
        $strings['ChangeConfiguration'] = 'Alterar Configuração';
        $strings['ResourceMinLength'] = 'As reservas devem durar pelo menos %s';
        $strings['ResourceMinLengthNone'] = 'Não há duração mínima de reserva';
        $strings['ResourceMaxLength'] = 'As reservas não podem durar mais de %s';
        $strings['ResourceMaxLengthNone'] = 'Não há duração máxima de reserva';
        $strings['ResourceRequiresApproval'] = 'As reservas devem ser aprovadas';
        $strings['ResourceRequiresApprovalNone'] = 'Reservas não necessitam aprovação';
        $strings['ResourcePermissionAutoGranted'] = 'A permissão é concedida automaticamente';
        $strings['ResourcePermissionNotAutoGranted'] = 'A permissão não é concedida automaticamente';
        $strings['ResourceMinNotice'] = 'As reservas devem ser feitas pelo menos %s antes do horário de início';
        $strings['ResourceMinNoticeNone'] = 'As reservas podem ser feitas até a hora atual';
        $strings['ResourceMinNoticeUpdate'] = 'As reservas devem ser atualizadas pelo menos %s antes do horário de início';
        $strings['ResourceMinNoticeNoneUpdate'] = 'As reservas podem ser atualizadas até a hora atual';
        $strings['ResourceMinNoticeDelete'] = 'As reservas devem ser excluídas pelo menos %s antes da hora de início';
        $strings['ResourceMinNoticeNoneDelete'] = 'As reservas podem ser excluídas até a hora atual';
        $strings['ResourceMaxNotice'] = 'As reservas não devem terminar mais de %s da hora atual';
        $strings['ResourceMaxNoticeNone'] = 'As reservas podem acabar a qualquer momento no futuro';
        $strings['ResourceBufferTime'] = 'Deve haver um intervalo de %s entre as reservas';
        $strings['ResourceBufferTimeNone'] = 'Não existe necessidade de intervalos entre as reservas';
        $strings['ResourceAllowMultiDay'] = 'As reservas podem ser feitas através de dias';
        $strings['ResourceNotAllowMultiDay'] = 'As reservas não podem ser feitas através de dias';
        $strings['ResourceCapacity'] = 'Este recurso tem uma capacidade de %s pessoas';
        $strings['ResourceCapacityNone'] = 'Esse recurso tem capacidade ilimitada';
        $strings['AddNewResource'] = 'Adicionar Novo Recurso';
        $strings['AddNewUser'] = 'Adicionar Novo Usuário';
        $strings['AddResource'] = 'Adicionar Recurso';
        $strings['Capacity'] = 'Capacidade';
        $strings['Access'] = 'Acesso';
        $strings['Duration'] = 'Duração';
        $strings['Active'] = 'Ativo';
        $strings['Inactive'] = 'Inativo';
        $strings['ResetPassword'] = 'Redefinir Senha';
        $strings['LastLogin'] = 'Último Acesso';
        $strings['Search'] = 'Pesquisar';
        $strings['ResourcePermissions'] = 'Permissões de Recursos';
        $strings['Reservations'] = 'Reservas';
        $strings['Groups'] = 'Grupos';
        $strings['Users'] = 'Usuários';
        $strings['AllUsers'] = 'Todos os Usuários';
        $strings['AllGroups'] = 'Todos os Grupos';
        $strings['AllSchedules'] = 'Todas as Agendas';
        $strings['UsernameOrEmail'] = 'Usuário ou Email';
        $strings['Members'] = 'Membros';
        $strings['QuickSlotCreation'] = 'Criar faixas a cada %s minutos entre %s e %s';
        $strings['ApplyUpdatesTo'] = 'Aplicar Atualizações Para';
        $strings['CancelParticipation'] = 'Cancelar Participação';
        $strings['Attending'] = 'Atender';
        $strings['QuotaConfiguration'] = 'Em %s para %s usuários em %s estão limitados a %s %s por %s';
        $strings['QuotaEnforcement'] = 'Cota imposta %s %s';
        $strings['reservations'] = 'reservas';
        $strings['reservation'] = 'reserva';
        $strings['ChangeCalendar'] = 'Alterar Calendário';
        $strings['AddQuota'] = 'Adicionar Cota';
        $strings['FindUser'] = 'Encontrar Usuário';
        $strings['Created'] = 'Criado';
        $strings['LastModified'] = 'Última Modificação';
        $strings['GroupName'] = 'Nome do Grupo';
        $strings['GroupMembers'] = 'Membros do Grupo';
        $strings['GroupRoles'] = 'Regras do Grupo';
        $strings['GroupAdmin'] = 'Administrador do Grupo';
        $strings['Actions'] = 'Ações';
        $strings['CurrentPassword'] = 'Senha Atual';
        $strings['NewPassword'] = 'Nova Senha';
        $strings['InvalidPassword'] = 'Senha atual está incorreta';
        $strings['PasswordChangedSuccessfully'] = 'Sua senha foi alterada com sucesso';
        $strings['SignedInAs'] = 'Conectado como';
        $strings['NotSignedIn'] = 'Você não está conectado';
        $strings['ReservationTitle'] = 'Título da reserva';
        $strings['ReservationDescription'] = 'Descrição da reserva';
        $strings['ResourceList'] = 'Recursos a serem reservados';
        $strings['Accessories'] = 'Acessórios';
        $strings['InvitationList'] = 'Convidados';
        $strings['AccessoryName'] = 'Nome do Acessório';
        $strings['QuantityAvailable'] = 'Quantidade Disponível';
        $strings['Resources'] = 'Recursos';
        $strings['Participants'] = 'Participantes';
        $strings['User'] = 'Usuário';
        $strings['Resource'] = 'Recurso';
        $strings['Status'] = 'Status';
        $strings['Approve'] = 'Aprovar';
        $strings['Page'] = 'Página';
        $strings['Rows'] = 'Linhas';
        $strings['Unlimited'] = 'Ilimitado';
        $strings['Email'] = 'E-Mail';
        $strings['EmailAddress'] = 'Endereço de E-mail';
        $strings['Phone'] = 'Telefone';
        $strings['Organization'] = 'Organização';
        $strings['Position'] = 'Posição';
        $strings['Language'] = 'Idioma';
        $strings['Permissions'] = 'Permissões';
        $strings['Reset'] = 'Redefinir';
        $strings['FindGroup'] = 'Encontrar Grupo';
        $strings['Manage'] = 'Gerenciar';
        $strings['None'] = 'Nenhum';
        $strings['AddToOutlook'] = 'Adicionar ao Calendário';
        $strings['Done'] = 'Concluído';
        $strings['RememberMe'] = 'Lembrar-se de Mim';
        $strings['FirstTimeUser?'] = 'É a sua primeira visita?';
        $strings['CreateAnAccount'] = 'Criar uma Conta';
        $strings['ViewSchedule'] = 'Ver Agendas';
        $strings['ForgotMyPassword'] = 'Esqueci Minha Senha';
        $strings['YouWillBeEmailedANewPassword'] = 'Você receberá um e-mail com uma nova senha gerada aleatoriamente';
        $strings['Close'] = 'Fechar';
        $strings['ExportToCSV'] = 'Exportar para CSV';
        $strings['OK'] = 'OK';
        $strings['Working'] = 'Trabalhando...';
        $strings['Login'] = 'Entrar';
        $strings['AdditionalInformation'] = 'Informações Adicionais';
        $strings['AllFieldsAreRequired'] = 'todos os campos são obrigatórios';
        $strings['Optional'] = 'opcional';
        $strings['YourProfileWasUpdated'] = 'Seu perfil foi atualizado';
        $strings['YourSettingsWereUpdated'] = 'Suas configurações foram atualizadas';
        $strings['Register'] = 'Registrar';
        $strings['SecurityCode'] = 'Código de Segurança';
        $strings['ReservationCreatedPreference'] = 'Quando eu criar uma reserva ou uma reserva for criada em meu nome';
        $strings['ReservationUpdatedPreference'] = 'Quando eu atualizar uma reserva ou uma reserva for atualizada em meu nome';
        $strings['ReservationDeletedPreference'] = 'Quando eu deletar uma reserva ou uma reserva for deletada em meu nome';
        $strings['ReservationApprovalPreference'] = 'Quando minha reserva pendente for aprovada';
        $strings['PreferenceSendEmail'] = 'Envie-me um e-mail';
        $strings['PreferenceNoEmail'] = 'Não me notificar';
        $strings['ReservationCreated'] = 'A sua reserva foi criada com sucesso!';
        $strings['ReservationUpdated'] = 'A sua reserva foi atualizada com sucesso!';
        $strings['ReservationRemoved'] = 'A sua reserva foi removida';
        $strings['ReservationRequiresApproval'] = 'Um ou mais recursos reservados necessitam de aprovação antes de serem usados. Esta reserva ficará pendente até a aprovação.';
        $strings['YourReferenceNumber'] = 'O seu número de referência é %s';
        $strings['ChangeUser'] = 'Alterar Usuário';
        $strings['MoreResources'] = 'Mais Recursos';
        $strings['ReservationLength'] = 'Duração da Reserva';
        $strings['ParticipantList'] = 'Lista de Participantes';
        $strings['AddParticipants'] = 'Adicionar Participantes';
        $strings['InviteOthers'] = 'Convidar Outros';
        $strings['AddResources'] = 'Adicionar Recursos';
        $strings['AddAccessories'] = 'Adicionar Acessórios';
        $strings['Accessory'] = 'Acessório';
        $strings['QuantityRequested'] = 'Quantidade Solicitada';
        $strings['CreatingReservation'] = 'Criando de Reserva';
        $strings['UpdatingReservation'] = 'Atualizando Reserva';
        $strings['DeleteWarning'] = 'Esta ação é permanente e irrecuperável!';
        $strings['DeleteAccessoryWarning'] = 'A exclusão desse acessório o removerá de todas as reservas.';
        $strings['AddAccessory'] = 'Adicionar Acessório';
        $strings['AddBlackout'] = 'Adicionar Horário Indisponível';
        $strings['AllResourcesOn'] = 'Todos os recursos em';
        $strings['Reason'] = 'Razão';
        $strings['BlackoutShowMe'] = 'Mostre-me reservas conflitantes';
        $strings['BlackoutDeleteConflicts'] = 'Excluir reservas conflitantes';
        $strings['Filter'] = 'Filtro';
        $strings['Between'] = 'Entre';
        $strings['CreatedBy'] = 'Criado Por';
        $strings['BlackoutCreated'] = 'Horário indisponível criado!';
        $strings['BlackoutNotCreated'] = 'Horário indisponível não pôde ser criado!';
        $strings['BlackoutUpdated'] = 'Horário indisponível atualizado!';
        $strings['BlackoutNotUpdated'] = 'Horário indisponível não foi atualizado!';
        $strings['BlackoutConflicts'] = 'Há conflitos entre horários indisponíveis.';
        $strings['ReservationConflicts'] = 'Há conflitos entre horários reservados.';
        $strings['UsersInGroup'] = 'Usuários neste grupo';
        $strings['Browse'] = 'Navegar';
        $strings['DeleteGroupWarning'] = 'A exclusão desse grupo removerá todas as permissões de recursos associadas.  Os usuários desse grupo podem perder o acesso aos recursos.';
        $strings['WhatRolesApplyToThisGroup'] = 'Quais regras se aplicam a esse grupo?';
        $strings['WhoCanManageThisGroup'] = 'Quem pode gerenciar este grupo?';
        $strings['WhoCanManageThisSchedule'] = 'Quem pode gerenciar essa agenda?';
        $strings['AllQuotas'] = 'Todas as Cotas';
        $strings['QuotaReminder'] = 'Lembre-se: As cotas são aplicadas com base no fuso horário da agenda.';
        $strings['AllReservations'] = 'Todas as Reservas';
        $strings['PendingReservations'] = 'Reservas Pendentes';
        $strings['Approving'] = 'Aprovar';
        $strings['MoveToSchedule'] = 'Mover para agenda';
        $strings['DeleteResourceWarning'] = 'A exclusão desse recurso excluirá todos os dados associados, incluindo';
        $strings['DeleteResourceWarningReservations'] = 'todas as reservas passadas, atuais e futuras associadas';
        $strings['DeleteResourceWarningPermissions'] = 'todas as atribuições de permissão';
        $strings['DeleteResourceWarningReassign'] = 'Por favor, reatribuir qualquer coisa que você não queira excuir antes de prosseguir';
        $strings['ScheduleLayout'] = 'Layout (todos os horários %s)';
        $strings['ReservableTimeSlots'] = 'Faixa de Horários Reserváveis';
        $strings['BlockedTimeSlots'] = 'Faixa de Horários Bloqueados';
        $strings['ThisIsTheDefaultSchedule'] = 'Esta é a agenda padrão';
        $strings['DefaultScheduleCannotBeDeleted'] = 'Agenda padrão não pode ser excluída';
        $strings['MakeDefault'] = 'Tornar Padrão';
        $strings['BringDown'] = 'Mover para Baixo';
        $strings['ChangeLayout'] = 'Alterar Layout';
        $strings['AddSchedule'] = 'Adicionar Agenda';
        $strings['StartsOn'] = 'Inicia em';
        $strings['NumberOfDaysVisible'] = 'Número de dias visíveis';
        $strings['UseSameLayoutAs'] = 'Usar mesmo layout como';
        $strings['Format'] = 'Formato';
        $strings['OptionalLabel'] = 'Rótulo Opcional';
        $strings['LayoutInstructions'] = 'Digite um intervalo por linha. Os intervalos devem ser fornecidos para todas as 24 horas do dia começando e terminando à 00:00.';
        $strings['AddUser'] = 'Adicionar Usuário';
        $strings['UserPermissionInfo'] = 'O acesso efetivo ao recurso pode ser diferente dependendo da função do usuário, permissões de grupo, ou definições de permissões externas';
        $strings['DeleteUserWarning'] = 'A exclusão desse usuário removerá todas as suas reservas atuais, futuras e históricas.';
        $strings['AddAnnouncement'] = 'Adicionar Anúncio';
        $strings['Announcement'] = 'Anúncio';
        $strings['Priority'] = 'Prioridade';
        $strings['Reservable'] = 'Disponível';
        $strings['Unreservable'] = 'Indisponível';
        $strings['Reserved'] = 'Reservados';
        $strings['MyReservation'] = 'Minhas Reservas';
        $strings['Pending'] = 'Pendente';
        $strings['Past'] = 'Passado';
        $strings['Restricted'] = 'Restrito';
        $strings['ViewAll'] = 'Ver Todos';
        $strings['MoveResourcesAndReservations'] = 'Mover recursos e reservas para';
        $strings['TurnOffSubscription'] = 'Ocultar do público';
        $strings['TurnOnSubscription'] = 'Mostrar ao público (RSS, iCalendar, Tablet, Monitor)';
        $strings['SubscribeToCalendar'] = 'Assinar este calendário';
        $strings['SubscriptionsAreDisabled'] = 'O administrador desativou as assinaturas de calendário';
        $strings['NoResourceAdministratorLabel'] = '(Nenhum administrador de recurso)';
        $strings['WhoCanManageThisResource'] = 'Quem pode gerenciar este recurso?';
        $strings['ResourceAdministrator'] = 'Administrador de Recurso';
        $strings['Private'] = 'Privado';
        $strings['Accept'] = 'Aceitar';
        $strings['Decline'] = 'Recusar';
        $strings['ShowFullWeek'] = 'Exibir semana';
        $strings['CustomAttributes'] = 'Atributos Personalizados';
        $strings['AddAttribute'] = 'Adicionar um atributo';
        $strings['EditAttribute'] = 'Atualizar um atributo';
        $strings['DisplayLabel'] = 'Nome de exibição';
        $strings['Type'] = 'Tipo';
        $strings['Required'] = 'Obrigatório';
        $strings['ValidationExpression'] = 'Expressão de validação';
        $strings['PossibleValues'] = 'Valores possíveis';
        $strings['SingleLineTextbox'] = 'Caixa de texto';
        $strings['MultiLineTextbox'] = 'Caixa de texto (múltiplas linhas)';
        $strings['Checkbox'] = 'Caixa de seleção';
        $strings['SelectList'] = 'Lista de seleção';
        $strings['CommaSeparated'] = 'Separados por vírgulas';
        $strings['Category'] = 'categoria';
        $strings['CategoryReservation'] = 'Reserva';
        $strings['CategoryGroup'] = 'Grupo';
        $strings['SortOrder'] = 'Ordem de exibição';
        $strings['Title'] = 'Título';
        $strings['AdditionalAttributes'] = 'Atributos adicionais';
        $strings['True'] = 'Verdadeiro';
        $strings['False'] = 'Falso';
        $strings['ForgotPasswordEmailSent'] = 'Um e-mail foi enviado para o endereço fornecido com instruções para redefinir sua senha';
        $strings['ActivationEmailSent'] = 'Você receberá um e-mail de ativação em breve.';
        $strings['AccountActivationError'] = 'Desculpe, não foi possível ativar sua conta.';
        $strings['Attachments'] = 'Anexos';
        $strings['AttachFile'] = 'Anexar arquivo';
        $strings['Maximum'] = 'Máx';
        $strings['NoScheduleAdministratorLabel'] = 'Nenhum Administrador de Agenda';
        $strings['ScheduleAdministrator'] = 'Administrador de Agenda';
        $strings['Total'] = 'Total';
        $strings['QuantityReserved'] = 'Quantidade reservada';
        $strings['AllAccessories'] = 'Todos os Acessórios';
        $strings['GetReport'] = 'Gerar Relatório';
        $strings['NoResultsFound'] = 'Nenhum resultado encontrado';
        $strings['SaveThisReport'] = 'Salvar este Relatório';
        $strings['ReportSaved'] = 'Relatório Salvo!';
        $strings['EmailReport'] = 'Enviar relatório por e-mail';
        $strings['ReportSent'] = 'Relatório enviado!';
        $strings['RunReport'] = 'Executar Relatório';
        $strings['NoSavedReports'] = 'Você não tem relatórios salvos.';
        $strings['CurrentWeek'] = 'Semana Atual';
        $strings['CurrentMonth'] = 'Mês Atual';
        $strings['AllTime'] = 'Qualquer Período';
        $strings['FilterBy'] = 'Filtrar Por';
        $strings['Select'] = 'Selecionar';
        $strings['TotalTime'] = 'Tempo';
        $strings['Count'] = 'Contagem';
        $strings['Usage'] = 'Utilização';
        $strings['AggregateBy'] = 'Agregar Por';
        $strings['Range'] = 'Período';
        $strings['Choose'] = 'Escolher';
        $strings['All'] = 'Todos';
        $strings['ViewAsChart'] = 'Exibir Gráfico';
        $strings['ReservedResources'] = 'Recursos Reservados';
        $strings['ReservedAccessories'] = 'Acessórios Reservados';
        $strings['ResourceUsageTimeBooked'] = 'Utilização de Recursos - Tempo Reservado';
        $strings['ResourceUsageReservationCount'] = 'Utilização de Recursos - Contagem de Reservas';
        $strings['Top20UsersTimeBooked'] = 'Top 20 Usuários - Tempo Reservado';
        $strings['Top20UsersReservationCount'] = 'Top 20 Usuários - Contagem de Reservas';
        $strings['ConfigurationUpdated'] = 'O arquivo de configuração foi atualizado';
        $strings['ConfigurationUiNotEnabled'] = 'Esta página não pode ser acessada. A $conf[\'settings\'][\'pages\'][\'enable.configuration\'] está definida como falsa ou ausente.';
        $strings['ConfigurationFileNotWritable'] = 'O arquivo de configuração não pode ser escrito. Por favor, verifique as permissões desde arquivo e tente novamente.';
        $strings['ConfigurationUpdateHelp'] = 'Consulte a seção de configuração do <a target=_blank href=%s>Arquivo de Ajuda</a> para obter a documentação sobre essas configurações.';
        $strings['GeneralConfigSettings'] = 'Configurações';
        $strings['UseSameLayoutForAllDays'] = 'Utilizar o mesmo layout para todos os dias';
        $strings['LayoutVariesByDay'] = 'Layout varia a cada dia';
        $strings['ManageReminders'] = 'Lembretes';
        $strings['ReminderUser'] = 'ID do usuário';
        $strings['ReminderMessage'] = 'Mensagem';
        $strings['ReminderAddress'] = 'Endereços';
        $strings['ReminderSendtime'] = 'Horário de envio';
        $strings['ReminderRefNumber'] = 'Número de referência da reserva';
        $strings['ReminderSendtimeDate'] = 'Data do lembrete';
        $strings['ReminderSendtimeTime'] = 'Hora do lembrete (HH:MM)';
        $strings['ReminderSendtimeAMPM'] = 'AM / PM';
        $strings['AddReminder'] = 'Adicionar lembrete';
        $strings['DeleteReminderWarning'] = 'Você tem certeza disto?';
        $strings['NoReminders'] = 'Você não possui mais lembretes.';
        $strings['Reminders'] = 'Lembretes';
        $strings['SendReminder'] = 'Enviar lembrete';
        $strings['ReminderBeforeStart'] = 'antes da hora de início';
        $strings['ReminderBeforeEnd'] = 'antes da hora de encerramento';
        $strings['Logo'] = 'Logo';
        $strings['CssFile'] = 'Arquivo CSS';
        $strings['ThemeUploadSuccess'] = 'Suas alterações foram salvas. Recarregue a página para que as alterações tenham efeito.';
        $strings['MakeDefaultSchedule'] = 'Tornar esta minha agenda padrão';
        $strings['DefaultScheduleSet'] = 'Agora esta é sua agenda padrão';
        $strings['FlipSchedule'] = 'Inverter o layout da agenda';
        $strings['Next'] = 'Próximo';
        $strings['Success'] = 'Sucesso';
        $strings['Participant'] = 'Participante';
        $strings['ResourceFilter'] = 'Filtro de recursos';
        $strings['ResourceGroups'] = 'Grupos de recursos';
        $strings['AddNewGroup'] = 'Adicionar um novo grupo';
        $strings['Quit'] = 'Sair';
        $strings['AddGroup'] = 'Adicionar Grupo';
        $strings['StandardScheduleDisplay'] = 'Utilizar exibição de agenda padrão';
        $strings['TallScheduleDisplay'] = 'Utilizar a exibição cd agenda vertical';
        $strings['WideScheduleDisplay'] = 'Utilizar a exibição de agenda horizontal';
        $strings['CondensedWeekScheduleDisplay'] = 'Utilizar a exibição de agenda condensada';
        $strings['ResourceGroupHelp1'] = 'Arraste e solte um grupo de recursos para reorganizá-los.';
        $strings['ResourceGroupHelp2'] = 'Clique com o botão direito no nome de um grupo de recursos para exibir ações adicionais.';
        $strings['ResourceGroupHelp3'] = 'Arraste e solte recursos para adicioná-los aos grupos.';
        $strings['ResourceGroupWarning'] = 'Se estiver usando grupos de recursos, cada recurso deve estar atribuído em pelo menos um grupo. Recursos não atribuídos não poderão ser reservados.';
        $strings['ResourceType'] = 'Tipo de recurso';
        $strings['AppliesTo'] = 'Aplica-se a';
        $strings['UniquePerInstance'] = 'Único por instância';
        $strings['AddResourceType'] = 'Adicionar tipo de recurso';
        $strings['NoResourceTypeLabel'] = '(Nenhum tipo de recurso selecionado)';
        $strings['ClearFilter'] = 'Limpar filtro';
        $strings['MinimumCapacity'] = 'Capacidade mínima';
        $strings['Color'] = 'Cor';
        $strings['Available'] = 'Disponível';
        $strings['Unavailable'] = 'Indisponível';
        $strings['Hidden'] = 'Oculto';
        $strings['ResourceStatus'] = 'Situação do recurso';
        $strings['CurrentStatus'] = 'Situação atual';
        $strings['AllReservationResources'] = 'Todos os recursos de reservas';
        $strings['File'] = 'Arquivo';
        $strings['BulkResourceUpdate'] = 'Atualização de recursos em massa';
        $strings['Unchanged'] = 'Inalterado';
        $strings['Common'] = 'Comum';
        $strings['AdminOnly'] = 'Somente para Administradores';
        $strings['AdvancedFilter'] = 'Filtro avançado';
        $strings['MinimumQuantity'] = 'Quantidade Mínima';
        $strings['MaximumQuantity'] = 'Quantidade Máxima';
        $strings['ChangeLanguage'] = 'Alterar idioma';
        $strings['AddRule'] = 'Adicionar regra';
        $strings['Attribute'] = 'Atributo';
        $strings['RequiredValue'] = 'Valor obrigatório';
        $strings['ReservationCustomRuleAdd'] = 'Use esta cor quando o atributo de reserva estiver definido com o seguinte valor';
        $strings['AddReservationColorRule'] = 'Adicionar regra de cor de reserva';
        $strings['LimitAttributeScope'] = 'Coletar em casos específicos';
        $strings['CollectFor'] = 'Recolher para';
        $strings['SignIn'] = 'Entrar';
        $strings['AllParticipants'] = 'Todos os participantes';
        $strings['RegisterANewAccount'] = 'Registrar Nova Conta';
        $strings['Dates'] = 'Datas';
        $strings['More'] = 'Mais';
        $strings['ResourceAvailability'] = 'Disponibilidade de recursos';
        $strings['UnavailableAllDay'] = 'Indisponível o dia todo';
        $strings['AvailableUntil'] = 'Disponível até';
        $strings['AvailableBeginningAt'] = 'Disponível a partir de';
        $strings['AvailableAt'] = 'Disponível em';
        $strings['AllResourceTypes'] = 'Todos os tipos de recursos';
        $strings['AllResourceStatuses'] = 'Todas as situações de recursos';
        $strings['AllowParticipantsToJoin'] = 'Permitir a adesão dos participantes';
        $strings['Join'] = 'Participar';
        $strings['YouAreAParticipant'] = 'Você é um participante desta reserva';
        $strings['YouAreInvited'] = 'Você foi convidado para esta reserva';
        $strings['YouCanJoinThisReservation'] = 'Você pode participar desta reserva';
        $strings['Import'] = 'Importar';
        $strings['GetTemplate'] = 'Obter modelo';
        $strings['UserImportInstructions'] = '<ul><li>O arquivo deve estar no formato CSV.</li>'
            . '<li>Nome de usuário e e-mail são campos obrigatórios.</li>'
            . '<li>A validade do atributo não será imposta.</li>'
            . '<li>Deixar outros campos em branco irá definir valores padrão e \'password\' como a senha do usuário.</li>'
            . '<li>Use o modelo fornecido como um exemplo.</li></ul>';
        $strings['RowsImported'] = 'Linhas importadas';
        $strings['RowsSkipped'] = 'Linhas ignoradas';
        $strings['Columns'] = 'Colunas';
        $strings['Reserve'] = 'Reserve';
        $strings['AllDay'] = 'Dia inteiro';
        $strings['Everyday'] = 'Todos os dias';
        $strings['IncludingCompletedReservations'] = 'Incluir reservas concluídas';
        $strings['NotCountingCompletedReservations'] = 'Não incluir reservas concluídas';
        $strings['RetrySkipConflicts'] = 'Ignorar reservas conflitantes';
        $strings['Retry'] = 'Tentar novamente';
        $strings['RemoveExistingPermissions'] = 'Remover permissões existentes?';
        $strings['Continue'] = 'Continuar';
        $strings['WeNeedYourEmailAddress'] = 'Necessitamos do seu endereço de email para reservar';
        $strings['ResourceColor'] = 'Cor do recurso';
        $strings['DateTime'] = 'Data e Hora';
        $strings['AutoReleaseNotification'] = 'Liberado automaticamente se o check-in não for efetuado dentro de %s minutos';
        $strings['RequiresCheckInNotification'] = 'Requer check-in/out';
        $strings['NoCheckInRequiredNotification'] = 'Não requer check-in/out';
        $strings['RequiresApproval'] = 'Requer aprovação';
        $strings['CheckingIn'] = 'Registro de entrada';
        $strings['CheckingOut'] = 'Registro de saída';
        $strings['CheckIn'] = 'Registro de entrada';
        $strings['CheckOut'] = 'Registro de saída';
        $strings['ReleasedIn'] = 'Liberado em';
        $strings['CheckedInSuccess'] = 'Sua entrada foi registrada';
        $strings['CheckedOutSuccess'] = 'Sua saída foi registrada';
        $strings['CheckInFailed'] = 'Não foi possível registrar sua entrada';
        $strings['CheckOutFailed'] = 'Não foi possível registrar sua saída';
        $strings['CheckInTime'] = 'Hora da entrada';
        $strings['CheckOutTime'] = 'Hora da saída';
        $strings['OriginalEndDate'] = 'Fim original';
        $strings['SpecificDates'] = 'Mostrar datas específicas';
        $strings['Guest'] = 'Convidado';
        $strings['ResourceDisplayPrompt'] = 'Recurso a ser exibido';
        $strings['Credits'] = 'Créditos';
        $strings['AvailableCredits'] = 'Créditos disponíveis';
        $strings['CreditUsagePerSlot'] = 'Requer %s de créditos por slot (fora do horário de pico)';
        $strings['PeakCreditUsagePerSlot'] = 'Requer %s créditos por slot (horário de pico)';
        $strings['CreditsRule'] = 'Você não possui créditos suficientes. Créditos necessários: %s. Créditos na conta: %s';
        $strings['PeakTimes'] = 'Horários de pico';
        $strings['AllYear'] = 'O ano todo';
        $strings['MoreOptions'] = 'Mais opções';
        $strings['SendAsEmail'] = 'Enviar como e-mail';
        $strings['UsersInGroups'] = 'Usuários nos grupos';
        $strings['UsersWithAccessToResources'] = 'Usuários com acesso aos recursos';
        $strings['AnnouncementSubject'] = 'Um novo anúncio foi publicado por %s';
        $strings['AnnouncementEmailNotice'] = 'os usuários receberão este anúncio por e-mail';
        $strings['Day'] = 'Dia';
        $strings['NotifyWhenAvailable'] = 'Notifique-me quando estiver disponível';
        $strings['AddingToWaitlist'] = 'Adicionando à lista de espera';
        $strings['WaitlistRequestAdded'] = 'Você será notificado se esse horário ficar disponível';
        $strings['PrintQRCode'] = 'Imprimir QR Code';
        $strings['FindATime'] = 'Encontrar horário';
        $strings['AnyResource'] = 'Qualquer recurso';
        $strings['ThisWeek'] = 'Semana atual';
        $strings['Hours'] = 'Horas';
        $strings['Minutes'] = 'Minutos';
        $strings['ImportICS'] = 'Importar do ICS';
        $strings['ImportQuartzy'] = 'Importar do Quartzy';
        $strings['OnlyIcs'] = 'Somente arquivos *.ics podem se importados.';
        $strings['IcsLocationsAsResources'] = 'Locais serão importados como recursos.';
        $strings['IcsMissingOrganizer'] = 'Qualquer evento que não tenha um organizador terá o proprietário configurado como o usuário atual.';
        $strings['IcsWarning'] = 'As regras de reserva não serão aplicadas se houver conflitos, duplicatas, etc.';
        $strings['BlackoutAroundConflicts'] = 'Indisponibilizar reservas conflitantes';
        $strings['DuplicateReservation'] = 'Duplicada';
        $strings['UnavailableNow'] = 'Indisponível no momento';
        $strings['ReserveLater'] = 'Reservar mais tarde';
        $strings['CollectedFor'] = 'Coletado para';
        $strings['IncludeDeleted'] = 'Incluir reservas excluídas';
        $strings['Deleted'] = 'Excluída';
        $strings['Back'] = 'Anterior';
        $strings['Forward'] = 'Próximo';
        $strings['DateRange'] = 'Período';
        $strings['Copy'] = 'Copiar';
        $strings['Detect'] = 'Detectar';
        $strings['Autofill'] = 'Preenchimento automático';
        $strings['NameOrEmail'] = 'Nome ou e-mail';
        $strings['ImportResources'] = 'Importar recursos';
        $strings['ExportResources'] = 'Exportar recursos';
        $strings['ResourceImportInstructions'] = '<ul><li>O arquivo deve estar no formato CSV com codificação UTF-8.</li>'
            . '<li>O nome é um campo obrigatório. Deixar outros campos em branco definirá os valores padrão.</li>'
            . '<li>As opções de status são \'Disponível\', \'Indisponível\' e \'Oculto\'..</li>'
            . '<li>A cor deve ser o valor hexadecimal, por exemplo, #ffffff.</li>'
            . '<li>As colunas Atribuição automática e Aprovação podem ser verdadeiras ou falsas.</li>'
            . '<li>A validade dos atributos não será imposta.</li>'
            . '<li>Separe vários grupos de recursos por vírgula.</li>'
            . '<li>As durações podem ser especificadas no formato #d#h#m ou HH:mm (1d3h30m ou 27:30 para 1 dia, 3 horas, 30 minutos)</li>'
            . '<li>Use o modelo fornecido como exemplo.</li></ul>';
        $strings['ReservationImportInstructions'] = '<ul><li>O arquivo deve estar no formato CSV com codificação UTF-8.</li>'
            . '<li>E-mail, nomes de recursos, início e fim são campos obrigatórios.</li>'
            . '<li>Início e fim exigem data e hora completas. O formato recomendado é AAAA-mm-dd HH:mm (2017-12-31 20:30).</li>'
            . '<li>Regras, conflitos e intervalos de tempo válidos não serão verificados.</li>'
            . '<li>As notificações não serão enviadas.</li>'
            . '<li>A validade dos atributos não será imposta.</li>'
            . '<li>Separe vários nomes de recursos por vírgula.</li>'
            . '<li>Use o modelo fornecido como exemplo.</li></ul>';
        $strings['AutoReleaseMinutes'] = 'Minutos de liberação automática';
        $strings['CreditsPeak'] = 'Créditos (horário de pico)';
        $strings['CreditsOffPeak'] = 'Créditos (fora do horário de pico)';
        $strings['ResourceMinLengthCsv'] = 'Duração mínima da reserva';
        $strings['ResourceMaxLengthCsv'] = 'Duração máxima da reserva';
        $strings['ResourceBufferTimeCsv'] = 'Intervalo entre reservas';
        $strings['ResourceMinNoticeAddCsv'] = 'Adicionar aviso prévio mínimo de reserva';
        $strings['ResourceMinNoticeUpdateCsv'] = 'Atualizar aviso prévio mínimo de reserva';
        $strings['ResourceMinNoticeDeleteCsv'] = 'Aviso prévio mínimo de eliminação de reserva';
        $strings['ResourceMaxNoticeCsv'] = 'Fim máximo da reserva';
        $strings['Export'] = 'Exportar';
        $strings['DeleteMultipleUserWarning'] = 'A exclusão desses usuários removerá todas as suas reservas atuais, futuras e históricas. Nenhum e-mail será enviado.';
        $strings['DeleteMultipleReservationsWarning'] = 'Nenhum e-mail será enviado.';
        $strings['ErrorMovingReservation'] = 'Erro ao mover a reserva';
        $strings['SelectUser'] = 'Selecione usuário';
        $strings['InviteUsers'] = 'Convidar usuários';
        $strings['InviteUsersLabel'] = 'Insira os endereços de e-mail para convidar as pessoas';
        $strings['ApplyToCurrentUsers'] = 'Aplicar aos usuários atuais';
        $strings['ReasonText'] = 'Texto do motivo';
        $strings['NoAvailableMatchingTimes'] = 'Não há horários disponíveis que correspondam à sua pesquisa';
        $strings['Schedules'] = 'Agendas';
        $strings['NotifyUser'] = 'Notificar usuário';
        $strings['UpdateUsersOnImport'] = 'Atualizar usuário existente se o e-mail já existir';
        $strings['UpdateResourcesOnImport'] = 'Atualizar recursos existentes se o nome já existir';
        $strings['Reject'] = 'Rejeitar';
        $strings['CheckingAvailability'] = 'Visualizar disponibilidade';
        $strings['CreditPurchaseNotEnabled'] = 'Você não ativou a compra créditos';
        $strings['CreditsEachCost1'] = 'Cada';
        $strings['CreditsEachCost2'] = 'crédito(s) custa(m)';
        $strings['CreditsCount'] = 'Quantidade de créditos';
        $strings['CreditsCost'] = 'Custo';
        $strings['Currency'] = 'Moeda';
        $strings['PayPalClientId'] = 'ID do cliente';
        $strings['PayPalSecret'] = 'Secret';
        $strings['PayPalEnvironment'] = 'Environment';
        $strings['Sandbox'] = 'Sandbox';
        $strings['Live'] = 'Ativo';
        $strings['StripePublishableKey'] = 'Publishable key';
        $strings['StripeSecretKey'] = 'Secret key';
        $strings['CreditsUpdated'] = 'O custo do crédito foi atualizado';
        $strings['GatewaysUpdated'] = 'Os gateways de pagamento foram atualizados';
        $strings['PurchaseSummary'] = 'Resumo da compra';
        $strings['EachCreditCosts'] = 'Cada crédito custa';
        $strings['Checkout'] = 'Pagamento';
        $strings['Quantity'] = 'Quantidade';
        $strings['CreditPurchase'] = 'Compra de crédito';
        $strings['EmptyCart'] = 'Seu carrinho está vazio.';
        $strings['BuyCredits'] = 'Comprar créditos';
        $strings['CreditsPurchased'] = 'créditos adquiridos.';
        $strings['ViewYourCredits'] = 'Ver seus créditos';
        $strings['TryAgain'] = 'Tente novamente';
        $strings['PurchaseFailed'] = 'Ocorreu um problema ao processar seu pagamento.';
        $strings['NoteCreditsPurchased'] = 'Créditos adquiridos';
        $strings['CreditsUpdatedLog'] = 'Créditos atualizados por %s';
        $strings['ReservationCreatedLog'] = 'Reserva criada. Número de referência %s';
        $strings['ReservationUpdatedLog'] = 'Reserva atualizada. Número de referência %s';
        $strings['ReservationDeletedLog'] = 'Reserva excluída. Número de referência %s';
        $strings['BuyMoreCredits'] = 'Comprar mais créditos';
        $strings['Transactions'] = 'Transações';
        $strings['Cost'] = 'Custo';
        $strings['PaymentGateways'] = 'Gateways de pagamento';
        $strings['CreditHistory'] = 'Histórico de crédito';
        $strings['TransactionHistory'] = 'Histórico de transações';
        $strings['Date'] = 'Data';
        $strings['Note'] = 'Nota';
        $strings['CreditsBefore'] = 'Créditos antes';
        $strings['CreditsAfter'] = 'Créditos depois';
        $strings['TransactionFee'] = 'Taxa de transação';
        $strings['InvoiceNumber'] = 'Número da fatura';
        $strings['TransactionId'] = 'ID da transação';
        $strings['Gateway'] = 'Gateway';
        $strings['GatewayTransactionDate'] = 'Data da transação do gateway';
        $strings['Refund'] = 'Reembolso';
        $strings['IssueRefund'] = 'Emitir reembolso';
        $strings['RefundIssued'] = 'Reembolso emitido com sucesso';
        $strings['RefundAmount'] = 'Montante do reembolso';
        $strings['AmountRefunded'] = 'Reembolsado';
        $strings['FullyRefunded'] = 'Totalmente reembolsado';
        $strings['YourCredits'] = 'Seus créditos';
        $strings['PayWithCard'] = 'Pagar com cartão';
        $strings['or'] = 'ou';
        $strings['CreditsRequired'] = 'Créditos necessários';
        $strings['AddToGoogleCalendar'] = 'Adicionar ao Google';
        $strings['Image'] = 'Imagem';
        $strings['ChooseOrDropFile'] = 'Escolha um arquivo ou arraste-o aqui';
        $strings['SlackBookResource'] = 'Reserve %s agora';
        $strings['SlackBookNow'] = 'Reservar agora';
        $strings['SlackNotFound'] = 'Não foi possível encontrar um recurso com esse nome. Reserve agora para iniciar uma nova reserva.';
        $strings['AutomaticallyAddToGroup'] = 'Adicionar automaticamente novos usuários a este grupo';
        $strings['GroupAutomaticallyAdd'] = 'Adicionar automaticamente';
        $strings['TermsOfService'] = 'Termos de serviço';
        $strings['EnterTermsManually'] = 'Inserir termos manualmente';
        $strings['LinkToTerms'] = 'Link dos Termos de Serviço';
        $strings['UploadTerms'] = 'Carregar Termos de serviço';
        $strings['RequireTermsOfServiceAcknowledgement'] = 'Exigir confirmação dos Termos de Serviço';
        $strings['UponReservation'] = 'No momento da reserva';
        $strings['UponRegistration'] = 'No momento do registro';
        $strings['ViewTerms'] = 'Visualizar termos de serviço';
        $strings['IAccept'] = 'Eu Aceito';
        $strings['TheTermsOfService'] = 'os Termos de Serviço';
        $strings['DisplayPage'] = 'Exibir página';
        $strings['AvailableAllYear'] = 'Ano inteiro';
        $strings['Availability'] = 'Disponibilidade';
        $strings['AvailableBetween'] = 'Disponível entre';
        $strings['ConcurrentYes'] = 'Os recursos podem ser reservados por mais de uma pessoa ao mesmo tempo';
        $strings['ConcurrentNo'] = 'Os recursos não podem ser reservados por mais de uma pessoa ao mesmo tempo';
        $strings['ScheduleAvailabilityEarly'] = 'Esta agenda ainda não está disponível. Está disponível';
        $strings['ScheduleAvailabilityLate'] = 'Esta agenda não está mais disponível. Estava disponível';
        $strings['ResourceImages'] = 'Imagens do recurso';
        $strings['FullAccess'] = 'Acesso total';
        $strings['ViewOnly'] = 'Somente visualização';
        $strings['Purge'] = 'Excluir definitivamente';
        $strings['UsersWillBeDeleted'] = 'os usuários serão excluídos';
        $strings['BlackoutsWillBeDeleted'] = 'os horários indisponíveis serão excluídos';
        $strings['ReservationsWillBePurged'] = 'as reservas serão excluídas definitivamente';
        $strings['ReservationsWillBeDeleted'] = 'as reservas serão apagadas';
        $strings['PermanentlyDeleteUsers'] = 'Excluir permanentemente os usuários que não estão conectados desde';
        $strings['DeleteBlackoutsBefore'] = 'Excluir horários indisponíveis antes de ';
        $strings['DeletedReservations'] = 'Reserva(s) excluída(s)';
        $strings['DeleteReservationsBefore'] = 'Excluir reservas anteriores a';
        $strings['SwitchToACustomLayout'] = 'Mudar para um layout personalizado';
        $strings['SwitchToAStandardLayout'] = 'Mudar para layout padrão';
        $strings['ThisScheduleUsesACustomLayout'] = 'Esta agenda utiliza um layout personalizado';
        $strings['ThisScheduleUsesAStandardLayout'] = 'Esta agenda usa um layout padrão';
        $strings['SwitchLayoutWarning'] = 'Tem certeza de que deseja alterar o tipo de layout? Isso removerá todos os intervalos existentes.';
        $strings['DeleteThisTimeSlot'] = 'Excluir este intervalo de horário?';
        $strings['Refresh'] = 'Atualizar';
        $strings['ViewReservation'] = 'Exibir reserva';
        $strings['PublicId'] = 'ID público';
        $strings['Public'] = 'Público';
        $strings['AtomFeedTitle'] = '%s Reservas';
        $strings['DefaultStyle'] = 'Estilo padrão';
        $strings['Standard'] = 'Padrão';
        $strings['Wide'] = 'Largo';
        $strings['Tall'] = 'Alto';
        $strings['EmailTemplate'] = 'Modelo de e-mail';
        $strings['SelectEmailTemplate'] = 'Selecionar modelo de e-mail';
        $strings['ReloadOriginalContents'] = 'Recarregar conteúdo original';
        $strings['UpdateEmailTemplateSuccess'] = 'Modelo de e-mail atualizado';
        $strings['UpdateEmailTemplateFailure'] = 'Não foi possível atualizar o modelo de e-mail. Por favor confirme se o diretório tem permissões de escrita';
        $strings['BulkResourceDelete'] = 'Exclusão de recursos em massa';
        $strings['NewVersion'] = 'Nova Versão!';
        $strings['WhatsNew'] = 'O que há de novo?';
        $strings['OnlyViewedCalendar'] = 'Esta agenda só pode ser visualizada a partir da exibição de calendário.';
        $strings['Grid'] = 'Grade';
        $strings['List'] = 'Lista';
        $strings['NoReservationsFound'] = 'Não foram encontradas reservas';
        $strings['EmailReservation'] = 'Enviar reserva por e-mail';
        $strings['AdHocMeeting'] = 'Reunião ad hoc';
        $strings['NextReservation'] = 'Próxima reserva';
        $strings['MissedCheckin'] = 'Entrada (Check-in) perdido';
        $strings['MissedCheckout'] = 'Saída (Check-out) perdido';
        $strings['Utilization'] = 'Utilização';
        $strings['SpecificTime'] = 'Horário específico';
        $strings['ReservationSeriesEndingPreference'] = 'Quando minha série de reservas recorrentes está terminando';
        $strings['NotAttending'] = 'Não comparecer';
        $strings['ViewAvailability'] = 'Visualizar disponibilidade';
        $strings['ReservationDetails'] = 'Detalhes da reserva';
        $strings['StartTime'] = 'Hora de início';
        $strings['EndTime'] = 'Hora de término';
        $strings['New'] = 'Novo';
        $strings['Updated'] = 'Atualizado';
        $strings['Custom'] = 'Personalizado';
        $strings['AddDate'] = 'Adicionar data';
        $strings['RepeatOn'] = 'Repetir em';
        $strings['ScheduleConcurrentMaximum'] = 'Um total de <b>%s</b> recursos podem ser reservados simultaneamente';
        $strings['ScheduleConcurrentMaximumNone'] = 'Não existe limite para o número de recursos reservados simultaneamente';
        $strings['ScheduleMaximumConcurrent'] = 'Número máximo de recursos que podem ser reservados simultaneamente';
        $strings['ScheduleMaximumConcurrentNote'] = 'Quando definido, o número total de recursos que podem ser reservados simultaneamente nesta agenda será limitado.';
        $strings['ScheduleResourcesPerReservationMaximum'] = 'Cada reserva é limitada a um máximo de <b>%s</b> recursos';
        $strings['ScheduleResourcesPerReservationNone'] = 'Não existe limite para o número de recursos por reserva';
        $strings['ScheduleResourcesPerReservation'] = 'Número máximo de recursos por reserva';
        $strings['ResourceConcurrentReservations'] = 'Permitir %s reservas simultâneas';
        $strings['ResourceConcurrentReservationsNone'] = 'Não permitir reservas simultâneas';
        $strings['AllowConcurrentReservations'] = 'Permitir reservas simultâneas';
        $strings['ResourceDisplayInstructions'] = 'Não foi selecionado nenhum recurso. Pode encontrar o endereço para exibir um recurso na Gestão da Aplicação, Recursos. O recurso deverá estar públicamente acessivel.';
        $strings['Owner'] = 'Proprietário';
        $strings['MaximumConcurrentReservations'] = 'Número máximo de reservas em simultâneas';
        $strings['NotifyUsers'] = 'Notificar usuários';
        $strings['Message'] = 'Mensagem';
        $strings['AllUsersWhoHaveAReservationInTheNext'] = 'Qualquer um com uma reserva na(o) seguinte';
        $strings['ChangeResourceStatus'] = 'Alterar o status do recurso';
        $strings['UpdateGroupsOnImport'] = 'Atualizar o grupo existente se o nome corresponder';
        $strings['GroupsImportInstructions'] = '<ul><li>O ficheiro deverá estar em formato CSV.</li>'
            . '<li>O nome é obrigatório.</li>'
            . '<li>As listas de membros deverão ser listas de emails separados por vírgulas.</li>'
            . '<li>Listas de membros vazias durante a atualização deixarão os membros inalterados.</li>'
            . '<li>As listas de permissões deverão ser listas de nomes de recursos separados por vírgulas.</li>'
            . '<li>Listas de permissões vazias durante a atualização de grupos deixarão as permissões inalteradas.</li>'
            . '<li>Utilize o modelo fornecido como exemplo.</li></ul>';
        $strings['PhoneRequired'] = 'O telefone é obrigatório';
        $strings['OrganizationRequired'] = 'A organização é obrigatória';
        $strings['PositionRequired'] = 'A posição é obrigatória';
        $strings['GroupMembership'] = 'Membros do grupo';
        $strings['AvailableGroups'] = 'Grupos disponiveis';
        $strings['CheckingAvailabilityError'] = 'Não é possível obter a disponibilidade do recurso - muitos recursos';
        // End Strings

        // Install
        $strings['InstallApplication'] = 'Instalar LibreBooking (apenas para MySQL)';
        $strings['IncorrectInstallPassword'] = 'Desculpe, esta senha está incorreta.';
        $strings['SetInstallPassword'] = 'Você deve escolher uma senha de instalação antes de iniciar.';
        $strings['InstallPasswordInstructions'] = 'Em %s, por favor, marque %s para uma senha que seja aleatória e difícil de adivinhar, então retorne para esta página.<br/>Você pode usar %s';
        $strings['NoUpgradeNeeded'] = 'Não existe necessidade de atualização. Executar o processo de instalação irá deletar todos os dados existentes e reinstalar o LibreBooking!';
        $strings['ProvideInstallPassword'] = 'Por favor forneça sua senha de instalação.';
        $strings['InstallPasswordLocation'] = 'Ela pode ser encontrada na %s em %s..';
        $strings['VerifyInstallSettings'] = 'Verifique as seguintes configurações padrão antes de continuar. Ou você pode alterá-las em %s.';
        $strings['DatabaseName'] = 'Nome da base de dados';
        $strings['DatabaseUser'] = 'Usuário da base de dados';
        $strings['DatabaseHost'] = 'Host da base de dados';
        $strings['DatabaseCredentials'] = 'Você deve fornecer as credenciais de um usuário MySQL que tenha privilégios de criar uma base de dados. Se não souber, entre em contato com seu Administrador de base de dados. Em muitos casos, o usuário root funcionará.';
        $strings['MySQLUser'] = 'Usuário do MySQL';
        $strings['InstallOptionsWarning'] = 'As opções a seguir provavelmente não funcionarão neste ambiente. Se estiver instalando em um ambiente hospedado, use as ferramentas do assistente do MySQL para concluir estas etapas.';
        $strings['CreateDatabase'] = 'Criar a base de dados';
        $strings['CreateDatabaseUser'] = 'Criar usuário da base de dados';
        $strings['PopulateExampleData'] = 'Importar amostra de dados. Crie uma conta de administrador: admin/password e conta de usuário: user/password';
        $strings['DataWipeWarning'] = 'Alerta: Isso excluirá todos os dados existentes';
        $strings['RunInstallation'] = 'Executar a instalação';
        $strings['UpgradeNotice'] = 'Você está atualizando a versão <b>%s</b> para a versão <b>%s</b>';
        $strings['RunUpgrade'] = 'Executar atualização';
        $strings['Executing'] = 'Executando';
        $strings['StatementFailed'] = 'Falha. Detalhes:';
        $strings['SQLStatement'] = 'Instrução SQL:';
        $strings['ErrorCode'] = 'Código de erro:';
        $strings['ErrorText'] = 'Texto do erro:';
        $strings['InstallationSuccess'] = 'A instalação foi concluída com sucesso!';
        $strings['RegisterAdminUser'] = 'Registre seu usuário administrador. Isto é necessário se você não importou os dados de amostra. Certifique-se de que $conf[\'settings\'][\'allow.self.registration\']= \'true\' no arquivo %s.';
        $strings['LoginWithSampleAccounts'] = 'Se você importar uma amostra de dado, você pode entrar com admin/senha para usuário admin ou user/senha para usuário básico.';
        $strings['InstalledVersion'] = 'Você está executando a versão %s do LibreBooking';
        $strings['InstallUpgradeConfig'] = 'É recomendável atualizar seu arquivo de configuração';
        $strings['InstallationFailure'] = 'Existem problemas com a instalação. Por favor corrijá-os e tente novamente.';
        $strings['ConfigureApplication'] = 'Configurar o LibreBooking';
        $strings['ConfigUpdateSuccess'] = 'Seu arquivo de configuração está atualizado!';
        $strings['ConfigUpdateFailure'] = 'Não foi possível atualizar automaticamente o arquivo de configuração. Por favor sobrescreva o conteúdo do config.php com o seguinte:';
        $strings['ScriptUrlWarning'] = 'Sua configuração <em>script.url</em> pode não estar correta. Atualmente é <strong>%s</strong>, mas acreditamos que deveria ser <strong>%s</strong>';
        // End Install

        // Errors
        $strings['LoginError'] = 'Não foi possível encontrar seu usuário ou senha';
        $strings['ReservationFailed'] = 'Sua reserva não pode ser efetuada';
        $strings['MinNoticeError'] = 'Esta reserva requer aviso prévio. A data e hora mais próxima que pode ser reservada é %s.';
        $strings['MinNoticeErrorUpdate'] = 'A alteração desta reserva requer aviso prévio. As reservas anteriores a %s não podem ser alteradas.';
        $strings['MinNoticeErrorDelete'] = 'A exclusão desta reserva requer aviso prévio. As reservas anteriores a %s não podem ser excluídas.';
        $strings['MaxNoticeError'] = 'Esta reserva não pode ser feita em um futuro tão distante. A última data e hora que pode ser reservada é %s.';
        $strings['MinDurationError'] = 'Esta reserva deve durar pelo menos %s.';
        $strings['MaxDurationError'] = 'Esta reserva não pode durar mais do que %s.';
        $strings['ConflictingAccessoryDates'] = 'Não há um número suficiente dos seguintes acessórios:';
        $strings['NoResourcePermission'] = 'Você não tem permissão para acessar um ou mais dos recursos solicitados.';
        $strings['ConflictingReservationDates'] = 'Há reservas conflitantes nas seguintes datas:';
        $strings['InstancesOverlapRule'] = 'Algumas instâncias da série de reservas se sobrepõem:';
        $strings['StartDateBeforeEndDateRule'] = 'A data e a hora de início devem ser anteriores à data e à hora de término.';
        $strings['StartIsInPast'] = 'A data e a hora de início não podem ser no passado.';
        $strings['EmailDisabled'] = 'O administrador desativou as notificações por e-mail.';
        $strings['ValidLayoutRequired'] = 'Intervalos devem ser fornecidas para todas as 24 horas do dia começando e terminando às 00:00.';
        $strings['CustomAttributeErrors'] = 'Existem problemas com os atributos adicionais que você forneceu:';
        $strings['CustomAttributeRequired'] = '%s é um campo obrigatório.';
        $strings['CustomAttributeInvalid'] = 'O valor fornecido para %s é inválido.';
        $strings['AttachmentLoadingError'] = 'Desculpe, houve um problema ao carregar o arquivo solicitado.';
        $strings['InvalidAttachmentExtension'] = 'Você pode carregar apenas arquivos do tipo: %s';
        $strings['InvalidStartSlot'] = 'A data e horário de início solicitados são inválidos.';
        $strings['InvalidEndSlot'] = 'A data e horário de término solicitados são inválidos.';
        $strings['MaxParticipantsError'] = '%s pode suportar apenas %s participantes.';
        $strings['ReservationCriticalError'] = 'Ocorreu um erro crítico ao salvar sua reserva. Se o problema persistir, entre em contato com o administrador do sistemas.';
        $strings['InvalidStartReminderTime'] = 'O horário de início do lembrete é inválidos.';
        $strings['InvalidEndReminderTime'] = 'O horário de término do lembrete é inválidos.';
        $strings['QuotaExceeded'] = 'Limite da cota excedido.';
        $strings['MultiDayRule'] = '%s não permite reservas através de múltiplos dias.';
        $strings['InvalidReservationData'] = 'Houve um problema com sua solicitação de reserva.';
        $strings['PasswordError'] = 'A senha deve conter pelo menos %s letras e pelo menos %s números.';
        $strings['PasswordErrorRequirements'] = 'A senha deve conter uma combinação de pelo menos %s letras maiúsculas e minúsculas e %s números.';
        $strings['NoReservationAccess'] = 'Você não tem permissão para alterar esta reserva.';
        $strings['PasswordControlledExternallyError'] = 'Sua senha é controlada por um sistema externo e não pode ser atualizada aqui.';
        $strings['AccessoryResourceRequiredErrorMessage'] = 'O acessório %s só pode ser reservado com recursos %s';
        $strings['AccessoryMinQuantityErrorMessage'] = 'Você deve reservar ao menos %s acessório(s) %s';
        $strings['AccessoryMaxQuantityErrorMessage'] = 'Você não pode reservar mais de %s acessório(s) %s';
        $strings['AccessoryResourceAssociationErrorMessage'] = 'O acessório \'%s\' não pode ser reservado com os recursos solicitados';
        $strings['NoResources'] = 'Você não adicionou nenhum recurso.';
        $strings['ParticipationNotAllowed'] = 'Você não tem permissão para participar desta reserva.';
        $strings['ReservationCannotBeCheckedInTo'] = 'O check-in desta reserva não pode ser efetuado.';
        $strings['ReservationCannotBeCheckedOutFrom'] = 'O check-out desta reserva não pode ser efetuado.';
        $strings['InvalidEmailDomain'] = 'O endereço de e-mail não é de um domínio válido';
        $strings['TermsOfServiceError'] = 'Você deve aceitar os Termos de Serviço';
        $strings['UserNotFound'] = 'Não foi possível encontrar o usuário';
        $strings['ScheduleAvailabilityError'] = 'Esta agenda está disponível entre %s e %s';
        $strings['ReservationNotFoundError'] = 'Reserva não encontrada';
        $strings['ReservationNotAvailable'] = 'Reserva indisponível';
        $strings['TitleRequiredRule'] = 'O título da reserva é obrigatório';
        $strings['DescriptionRequiredRule'] = 'A descrição da reserva é obrigatória';
        $strings['WhatCanThisGroupManage'] = 'O que esse grupo pode gerenciar?';
        $strings['ReservationParticipationActivityPreference'] = 'Quando alguém entra ou sai da minha reserva';
        $strings['RegisteredAccountRequired'] = 'Somente usuários registrados podem fazer reservas';
        $strings['InvalidNumberOfResourcesError'] = 'O número máximo de recursos que podem ser reservados numa única reserva é %s';
        $strings['ScheduleTotalReservationsError'] = 'Esta agenda só permite que %s recursos sejam reservados simultaneamente. Esta reserva violaria esse limite nas seguintes datas:';
        // End Errors

        // Page Titles
        $strings['CreateReservation'] = 'Criar Reserva';
        $strings['EditReservation'] = 'Editar Reserva';
        $strings['LogIn'] = 'Entrar';
        $strings['ManageReservations'] = 'Reservas';
        $strings['AwaitingActivation'] = 'Aguardando Ativação';
        $strings['PendingApproval'] = 'Aguardando Aprovação';
        $strings['ManageSchedules'] = 'Agendas';
        $strings['ManageResources'] = 'Recursos';
        $strings['ManageAccessories'] = 'Acessórios';
        $strings['ManageUsers'] = 'Usuários';
        $strings['ManageGroups'] = 'Grupos';
        $strings['ManageQuotas'] = 'Cotas';
        $strings['ManageBlackouts'] = 'Horários Indisponíveis';
        $strings['MyDashboard'] = 'Meu Painel de Controle';
        $strings['ServerSettings'] = 'Configurações do Servidor';
        $strings['Dashboard'] = 'Painel de Controle';
        $strings['Help'] = 'Ajuda';
        $strings['Administration'] = 'Administração';
        $strings['About'] = 'Sobre';
        $strings['Bookings'] = 'Reservas';
        $strings['Schedule'] = 'Agenda';
        $strings['Account'] = 'Conta';
        $strings['EditProfile'] = 'Editar Meu Perfil';
        $strings['FindAnOpening'] = 'Encontrar uma vaga';
        $strings['OpenInvitations'] = 'Abrir Convites';
        $strings['ResourceCalendar'] = 'Calendário de Recursos';
        $strings['Reservation'] = 'Nova Reserva';
        $strings['Install'] = 'Instalação';
        $strings['ChangePassword'] = 'Alterar Senha';
        $strings['MyAccount'] = 'Minha Conta';
        $strings['Profile'] = 'Perfil';
        $strings['ApplicationManagement'] = 'Gerenciamento da Aplicação';
        $strings['ForgotPassword'] = 'Esqueci minha Senha';
        $strings['NotificationPreferences'] = 'Preferências de Notificação';
        $strings['ManageAnnouncements'] = 'Anúncios';
        $strings['Responsibilities'] = 'Responsabilidades';
        $strings['GroupReservations'] = 'Reservas de Grupos';
        $strings['ResourceReservations'] = 'Reservas de Recursos';
        $strings['Customization'] = 'Personalização';
        $strings['Attributes'] = 'Atributos';
        $strings['AccountActivation'] = 'Ativação de Conta';
        $strings['ScheduleReservations'] = 'Reservas na Agenda';
        $strings['Reports'] = 'Relatórios';
        $strings['GenerateReport'] = 'Criar Novo Relatório';
        $strings['MySavedReports'] = 'Meus Relatórios Salvos';
        $strings['CommonReports'] = 'Relatórios Gerais';
        $strings['ViewDay'] = 'Visualizar dia';
        $strings['Group'] = 'Grupo';
        $strings['ManageConfiguration'] = 'Configurar Aplicação';
        $strings['LookAndFeel'] = 'Temas';
        $strings['ManageResourceGroups'] = 'Grupo de Recursos';
        $strings['ManageResourceTypes'] = 'Tipos de Recursos';
        $strings['ManageResourceStatus'] = 'Situação dos Recursos';
        $strings['ReservationColors'] = 'Cores das reservas';
        $strings['SearchReservations'] = 'Pesquisar reservas';
        $strings['ManagePayments'] = 'Pagamentos';
        $strings['ViewCalendar'] = 'Exibir calendário';
        $strings['DataCleanup'] = 'Limpeza de dados';
        $strings['ManageEmailTemplates'] = 'Gerenciar Modelos de E-mail';
        // End Page Titles

        // Day representations
        $strings['DaySundaySingle'] = 'D';
        $strings['DayMondaySingle'] = 'S';
        $strings['DayTuesdaySingle'] = 'T';
        $strings['DayWednesdaySingle'] = 'Q';
        $strings['DayThursdaySingle'] = 'Q';
        $strings['DayFridaySingle'] = 'S';
        $strings['DaySaturdaySingle'] = 'S';

        $strings['DaySundayAbbr'] = 'Dom';
        $strings['DayMondayAbbr'] = 'Seg';
        $strings['DayTuesdayAbbr'] = 'Ter';
        $strings['DayWednesdayAbbr'] = 'Qua';
        $strings['DayThursdayAbbr'] = 'Qui';
        $strings['DayFridayAbbr'] = 'Sex';
        $strings['DaySaturdayAbbr'] = 'Sab';
        // End Day representations

        // Email Subjects
        $strings['ReservationApprovedSubject'] = 'Sua reserva foi aprovada';
        $strings['ReservationCreatedSubject'] = 'Sua reserva foi criada';
        $strings['ReservationUpdatedSubject'] = 'Sua reserva foi atualizada';
        $strings['ReservationDeletedSubject'] = 'Sua reserva foi removida';
        $strings['ReservationCreatedAdminSubject'] = 'Notificação: Uma reserva foi criada';
        $strings['ReservationUpdatedAdminSubject'] = 'Notificação: Uma reserva foi atualizada';
        $strings['ReservationDeleteAdminSubject'] = 'Notificação: Uma reserva foi removida';
        $strings['ReservationApprovalAdminSubject'] = 'Notificação: Reserva requer sua aprovação';
        $strings['ParticipantAddedSubject'] = 'Notificação de Participação na Reserva';
        $strings['ParticipantDeletedSubject'] = 'Reserva removida';
        $strings['InviteeAddedSubject'] = 'Convite de Reserva';
        $strings['ResetPasswordRequest'] = 'Solicitação de Redefinição de Senha';
        $strings['ActivateYourAccount'] = 'Por favor, ative sua conta';
        $strings['ReportSubject'] = 'Seu relatório solicitado (%s)';
        $strings['ReservationStartingSoonSubject'] = 'A reserva para %s começará em breve';
        $strings['ReservationEndingSoonSubject'] = 'A reserva para %s terminará em breve';
        $strings['UserAdded'] = 'Um novo usuário foi adicionado';
        $strings['UserDeleted'] = 'Conta de usuário para %s foi excluída por %s';
        $strings['GuestAccountCreatedSubject'] = 'Detalhes da sua conta %s';
        $strings['AccountCreatedSubject'] = 'Detalhes da sua conta %s';
        $strings['InviteUserSubject'] = '%s convidou você para participar do sistema %s';
        $strings['ReservationApprovedSubjectWithResource'] = 'A reserva foi aprovada para %s';
        $strings['ReservationCreatedSubjectWithResource'] = 'Reserva criada para %s';
        $strings['ReservationUpdatedSubjectWithResource'] = 'Reserva atualizada para %s';
        $strings['ReservationDeletedSubjectWithResource'] = 'Reserva removida para %s';
        $strings['ReservationCreatedAdminSubjectWithResource'] = 'Notificação: Reserva criada para %s';
        $strings['ReservationUpdatedAdminSubjectWithResource'] = 'Notificação: Reserva atualizada para %s';
        $strings['ReservationDeleteAdminSubjectWithResource'] = 'Notificação: Reserva removida para %s';
        $strings['ReservationApprovalAdminSubjectWithResource'] = 'Notificação: Reserva para %s requer sua aprovação';
        $strings['ParticipantAddedSubjectWithResource'] = '%s adicionou você a uma reserva para %s';
        $strings['ParticipantUpdatedSubjectWithResource'] = '%s atualizou uma reserva para %s';
        $strings['ParticipantDeletedSubjectWithResource'] = '%s removeu uma reserva para %s';
        $strings['InviteeAddedSubjectWithResource'] = '%s convidou você para uma reserva de %s';
        $strings['MissedCheckinEmailSubject'] = 'Check-in perdido para %s';
        $strings['ReservationShareSubject'] = '%s compartilhou uma reserva para %s';
        $strings['ReservationSeriesEndingSubject'] = 'Série de reservas para %s está terminando em %s';
        $strings['ReservationParticipantAccept'] = '%s aceitou seu convite de reserva para %s em %s';
        $strings['ReservationParticipantDecline'] = '%s recusou seu convite de reserva para %s em %s';
        $strings['ReservationParticipantJoin'] = '%s juntou-se à sua reserva para %s em %s';
        $strings['ReservationAvailableSubject'] = '%s está disponível em %s';
        $strings['ResourceStatusChangedSubject'] = 'A disponibilidade de %s foi alterada';
        // End Email Subjects

        //NEEDS CHECKING
        //Past Reservations
        $strings['NoPastReservations'] = 'Você não possui reservas anteriores';
        $strings['PastReservations'] = 'Reservas Anteriores';
        $strings['AllNoPastReservations'] = 'Não há reservas anteriores nos últimos %s dias';
        $strings['AllPastReservations'] = 'Todas as Reservas Anteriores';
        $strings['Yesterday'] = 'Ontem';
        $strings['EarlierThisWeek'] = 'Antes desta semana';
        $strings['PreviousWeek'] = 'Semana anterior';
        //End Past Reservations

        //Group Upcoming Reservations
        $strings['NoGroupUpcomingReservations'] = 'Seu grupo não tem reservas futuras';
        $strings['GroupUpcomingReservations'] = 'Reservas Futuras do(s) meu(s) Grupo(s)';
        //End Group Upcoming Reservations

        //Facebook Login SDK Error
        $strings['FacebookLoginErrorMessage'] = 'Ocorreu um erro ao fazer login com o Facebook. Por favor, tente novamente.';
        //End Facebook Login SDK Error

        //Pending Approval Reservations in Dashboard
        $strings['NoPendingApprovalReservations'] = 'Você não tem reservas aguardando aprovação';
        $strings['PendingApprovalReservations'] = 'Reservas aguardando aprovação';
        $strings['LaterThisMonth'] = 'Mais tarde neste mês';
        $strings['LaterThisYear'] = 'Mais tarde neste ano';
        $strings['Remaining'] = 'Restante';        
        //End Pending Approval Reservations in Dashboard

        //Missing Check In/Out Reservations in Dashboard
        $strings['NoMissingCheckOutReservations'] = 'Não há reservas de check-out em falta';
        $strings['MissingCheckOutReservations'] = 'Reservas de Check Out em Falta';        
        //End Missing Check In/Out Reservations in Dashboard

        //Schedule Resource Permissions
        $strings['NoResourcePermissions'] = 'Não é possível ver os detalhes da reserva porque você não tem permissões para nenhum dos recursos nesta reserva';
        //End Schedule Resource Permissions
        //END NEEDS CHECKING

        // Currently unused strings
        $strings['of'] = 'de';
        $strings['ViewWeek'] = 'Ver Semana';
        $strings['ViewMonth'] = 'Ver Mês';
        $strings['CurrentTime'] = 'Hora Atual';
        $strings['ImageUploadDirectory'] = 'Diretório para enviar imagem física';
        $strings['ChangePermissions'] = 'Tente aplicar as permissões corretas';
        $strings['PwComplexity'] = 'A senha deve ter pelo menos 6 caracteres com uma combinação de letras, números e símbolos.';
        // End of Currently unused strings


        $this->Strings = $strings;

        return $this->Strings;
    }

    /**
     * @return array
     */
    protected function _LoadDays()
    {
        $days = parent::_LoadDays();

        /***
         * DAY NAMES
         * All of these arrays MUST start with Sunday as the first element
         * and go through the seven day week, ending on Saturday
         ***/
        // The full day name
        $days['full'] = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
        // The three letter abbreviation
        $days['abbr'] = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'];
        // The two letter abbreviation
        $days['two'] = ['Do', 'Se', 'Te', 'Qu', 'Qu', 'Se', 'Sa'];
        // The one letter abbreviation
        $days['letter'] = ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'];

        $this->Days = $days;

        return $this->Days;
    }

    /**
     * @return array
     */
    protected function _LoadMonths()
    {
        $months = parent::_LoadMonths();

        /***
         * MONTH NAMES
         * All of these arrays MUST start with January as the first element
         * and go through the twelve months of the year, ending on December
         ***/
        // The full month name
        $months['full'] = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
        // The three letter month name
        $months['abbr'] = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];

        $this->Months = $months;

        return $this->Months;
    }

    /**
     * @return array
     */
    protected function _LoadLetters()
    {
        $this->Letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];

        return $this->Letters;
    }

    protected function _GetHtmlLangCode()
    {
        return 'pt_br';
    }
}
