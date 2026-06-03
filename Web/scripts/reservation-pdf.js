(function () {
  'use strict';

  function getConfig() {
    return window.ReservationPdfConfig || null;
  }

  function getCheckboxAttributeType(config) {
    return Number(config.customAttributeTypeCheckbox);
  }

  function getSelectedResourceIds() {
    const resourceIds = [];
    const primaryResource = document.getElementById('primaryResourceId');
    if (primaryResource) {
      resourceIds.push(parseInt(primaryResource.value, 10));
    }

    document.querySelectorAll('#additionalResources .resourceId').forEach((element) => {
      resourceIds.push(parseInt(element.value, 10));
    });

    return resourceIds.filter((resourceId) => !isNaN(resourceId));
  }

  async function loadCustomAttributesData() {
    const uid = document.getElementById('userId');
    const rn = document.getElementById('referenceNumber');
    const ro = document.getElementById('reservation-box');
    const params = new URLSearchParams({
      uid: uid ? uid.value : '',
      rn: rn ? rn.value : '',
      ro: String(ro ? ro.classList.contains('readonly') : false),
    });

    getSelectedResourceIds().forEach((resourceId) => params.append('rid[]', resourceId));

    try {
      const response = await fetch('ajax/reservation_attributes_print.php?' + params.toString(), {
        credentials: 'same-origin',
        headers: { Accept: 'application/json' },
      });

      if (!response.ok) {
        throw new Error('fetch failed');
      }

      return (await response.json()) || {};
    } catch (error) {
      console.warn('Error loading attributes', error);
      return {};
    }
  }

  function loadImageDataUrl(url) {
    return new Promise((resolve) => {
      const image = new Image();

      image.crossOrigin = 'anonymous';
      image.onload = function () {
        try {
          const canvas = document.createElement('canvas');
          canvas.width = image.naturalWidth || image.width;
          canvas.height = image.naturalHeight || image.height;
          canvas.getContext('2d').drawImage(image, 0, 0);
          resolve(canvas.toDataURL('image/png'));
        } catch {
          resolve(null);
        }
      };
      image.onerror = function () {
        resolve(null);
      };
      image.src = url;
    });
  }

  function pdfCell(text, style, extra) {
    const cell = {
      text: text == null ? '' : String(text),
    };

    if (style) {
      cell.style = style;
    }

    if (extra) {
      Object.assign(cell, extra);
    }

    return cell;
  }

  function pdfSpanCell(text, style, span, extra) {
    const cell = pdfCell(text, style, extra);
    cell.colSpan = span;
    return cell;
  }

  function pdfEmptyCells(count) {
    return Array.from({ length: count }, function () {
      return {};
    });
  }

  function pdfTable(body, widths, headerRows) {
    return {
      margin: [0, 0, 0, 6],
      layout: {
        hLineWidth: function () {
          return 0.4;
        },
        vLineWidth: function () {
          return 0.4;
        },
        hLineColor: function () {
          return '#c9d1dc';
        },
        vLineColor: function () {
          return '#c9d1dc';
        },
        paddingLeft: function () {
          return 6;
        },
        paddingRight: function () {
          return 6;
        },
        paddingTop: function () {
          return 4;
        },
        paddingBottom: function () {
          return 4;
        },
      },
      table: {
        headerRows: headerRows || 0,
        widths: widths,
        body: body,
      },
    };
  }

  function buildHeader(config, logoDataUrl) {
    const content = [];

    content.push({
      columns: [
        logoDataUrl ? { image: logoDataUrl, fit: [64, 40], width: 64, margin: [0, 0, 10, 0] } : { text: '' },
        {
          width: '*',
          stack: [
            { text: config.appTitle, style: 'documentTitle' },
            { text: config.reservationDetailsTitle, style: 'documentSubtitle' },
          ],
          margin: [0, 2, 0, 0],
        },
      ],
      columnGap: 10,
      margin: [0, 0, 0, 4],
    });

    content.push({
      canvas: [
        {
          type: 'line',
          x1: 0,
          y1: 0,
          x2: 530,
          y2: 0,
          lineWidth: 0.8,
          lineColor: '#8b99ad',
        },
      ],
      margin: [0, 0, 0, 8],
    });

    content.push({
      text: config.referenceNumberLabel + ': ' + config.referenceNumber,
      style: 'sectionHeading',
      alignment: 'left',
    });

    if (config.showUserDetailsAndReservationDetails) {
      content.push(
        pdfTable(
          [[pdfCell(config.userLabel, 'labelCell'), pdfCell(config.reservationUserName, 'valueCell')]],
          [150, '*']
        )
      );
    }

    return content;
  }

  function buildDetails(config, durationText) {
    const isCustomRepeat = config.isRecurring && config.isCustomRepeat;
    const detailsBody = [
      [
        pdfCell(config.beginDateLabel, 'labelCell'),
        pdfCell(config.beginDateValue, 'valueCell'),
        pdfCell(config.endDateLabel, 'labelCell'),
        pdfCell(config.endDateValue, 'valueCell'),
      ],
      [pdfCell(config.reservationLengthLabel, 'labelCell'), pdfSpanCell(durationText, 'valueCell', 3)].concat(
        pdfEmptyCells(2)
      ),
      [pdfCell(config.repeatPromptLabel, 'labelCell')]
        .concat(
          config.isRecurring && !isCustomRepeat
            ? [
                pdfCell(config.repeatTypeLabel, 'valueCell'),
                pdfCell(config.repeatInterval, 'valueCell'),
                pdfCell(config.repeatEveryLabel, 'valueCell'),
              ]
            : [pdfSpanCell(config.repeatTypeLabel, 'valueCell', 3)]
        )
        .concat(config.isRecurring && !isCustomRepeat ? [] : pdfEmptyCells(2)),
    ];

    if (config.isRecurring) {
      if (isCustomRepeat && config.repeatCustomDates.length > 0) {
        detailsBody.push(
          [
            pdfCell(config.repeatOnLabel, 'labelCell'),
            pdfSpanCell(config.repeatCustomDates.join(', '), 'valueCell', 3),
          ].concat(pdfEmptyCells(2))
        );
      }

      if (!isCustomRepeat && config.repeatMonthlyTypeLabel) {
        detailsBody.push(
          [pdfCell(config.typeLabel, 'labelCell'), pdfSpanCell(config.repeatMonthlyTypeLabel, 'valueCell', 3)].concat(
            pdfEmptyCells(2)
          )
        );
      }

      if (!isCustomRepeat && config.repeatWeekdays.length > 0) {
        detailsBody.push(
          [
            pdfCell(config.daysLabel, 'labelCell'),
            pdfSpanCell(config.repeatWeekdays.join(', '), 'valueCell', 3),
          ].concat(pdfEmptyCells(2))
        );
      }

      if (!isCustomRepeat && config.repeatUntilDate) {
        detailsBody.push(
          [
            pdfCell(config.repeatUntilPromptLabel, 'labelCell'),
            pdfSpanCell(config.repeatUntilDate, 'valueCell', 3),
          ].concat(pdfEmptyCells(2))
        );
      }
    }

    return pdfTable(detailsBody, [95, '*', 85, '*']);
  }

  function buildAttributes(config, attributesData) {
    if (!attributesData || Object.keys(attributesData).length === 0) {
      return null;
    }

    const attributesBody = [
      [pdfSpanCell(config.additionalAttributesLabel, 'tableHeader', 2, { alignment: 'left' }), {}],
    ];
    const checkboxAttributeType = getCheckboxAttributeType(config);

    Object.values(attributesData).forEach((attribute) => {
      const attributeType = Number(attribute[0]);
      const attributeValue = String(attribute[2] ?? '').toLowerCase();
      const isChecked = attributeValue === '1' || attributeValue === 'true' || attributeValue === 'on';

      if (attributeType === checkboxAttributeType) {
        attributesBody.push([
          pdfCell(attribute[1], 'labelCell'),
          pdfCell(isChecked ? 'X' : '', 'valueCell', { alignment: 'left' }),
        ]);
        return;
      }

      attributesBody.push([pdfCell(attribute[1], 'labelCell'), pdfCell(attribute[2], 'valueCell')]);
    });

    return pdfTable(attributesBody, [220, '*'], 1);
  }

  function buildReservationPdfDefinition(config, logoDataUrl, attributesData) {
    const durationElement = document.getElementsByClassName('durationText').item(0);
    const durationText = durationElement ? durationElement.innerText : '';
    const reminders = [];
    const content = [];

    Array.prototype.push.apply(content, buildHeader(config, logoDataUrl));
    content.push(buildDetails(config, durationText));

    const resourcesBody = [
      [
        pdfCell(config.resourcesHeaderLabel, 'tableHeader'),
        pdfCell(config.requiresApprovalLabel, 'tableHeaderCenter'),
        pdfCell(config.requiresCheckInNotificationLabel, 'tableHeaderCenter'),
        pdfCell(config.releasedInLabel, 'tableHeaderCenter'),
      ],
    ];

    config.resources.forEach(function (resource) {
      resourcesBody.push([
        pdfCell(resource.name, 'valueCell'),
        pdfCell(resource.requiresApproval ? 'X' : '', 'centerCell'),
        pdfCell(resource.requiresCheckIn ? 'X' : '', 'centerCell'),
        pdfCell(resource.releasedIn, 'centerCell'),
      ]);
    });

    content.push(pdfTable(resourcesBody, ['*', 72, 88, 72], 1));

    if (config.showAccessories && config.accessories.length > 0) {
      const accessoriesBody = [
        [pdfCell(config.accessoriesHeaderLabel, 'tableHeader'), pdfCell(config.quantityLabel, 'tableHeaderCenter')],
      ];

      config.accessories.forEach(function (accessory) {
        accessoriesBody.push([pdfCell(accessory.name, 'valueCell'), pdfCell(accessory.quantity, 'centerCell')]);
      });

      content.push(pdfTable(accessoriesBody, ['*', 75], 1));
    }

    if (config.showParticipants && config.participants.length > 0) {
      const participantsBody = [
        [pdfCell(config.participantsHeaderLabel, 'tableHeader'), pdfCell(config.emailLabel, 'tableHeader')],
      ];

      config.participants.forEach(function (participant) {
        participantsBody.push([pdfCell(participant.fullName, 'valueCell'), pdfCell(participant.email, 'valueCell')]);
      });

      content.push(pdfTable(participantsBody, ['*', '*'], 1));
    }

    if (config.showInvitees && config.invitees.length > 0) {
      const inviteesBody = [
        [pdfCell(config.invitationListHeaderLabel, 'tableHeader'), pdfCell(config.emailLabel, 'tableHeader')],
      ];

      config.invitees.forEach(function (invitee) {
        inviteesBody.push([pdfCell(invitee.fullName, 'valueCell'), pdfCell(invitee.email, 'valueCell')]);
      });

      content.push(pdfTable(inviteesBody, ['*', '*'], 1));
    }

    content.push(
      pdfTable(
        [
          [pdfCell(config.reservationTitleLabel, 'tableHeader')],
          [pdfCell(config.reservationTitle, 'valueCell')],
          [pdfCell(config.reservationDescriptionLabel, 'tableHeader')],
          [pdfCell(config.reservationDescription, 'valueCell')],
        ],
        ['*']
      )
    );

    const attributesTable = buildAttributes(config, attributesData);
    if (attributesTable) {
      content.push(attributesTable);
    }

    if (config.remindersEnabled && config.reminders.length > 0) {
      config.reminders.forEach(function (reminder) {
        reminders.push([reminder.time, reminder.interval, reminder.text].join(' '));
      });

      content.push(
        pdfTable(
          [[pdfCell(config.sendReminderLabel, 'labelCell'), pdfCell(reminders.join('\n'), 'valueCell')]],
          [150, '*']
        )
      );
    }

    if (config.attachments.length > 0) {
      const attachmentsBody = [
        [pdfCell(config.attachmentsLabel + ' (' + config.attachments.length + ')', 'tableHeader')],
      ];

      config.attachments.forEach(function (attachmentName) {
        attachmentsBody.push([pdfCell(attachmentName, 'valueCell')]);
      });

      content.push(pdfTable(attachmentsBody, ['*']));
    }

    if (config.showTermsAcceptance) {
      content.push(pdfTable([[pdfCell(config.acceptTermsLabel, 'labelCell'), pdfCell('X', 'centerCell')]], ['*', 40]));
    }

    return {
      pageSize: 'A4',
      pageMargins: [24, 24, 24, 24],
      content: content,
      defaultStyle: {
        fontSize: 9,
        lineHeight: 1.15,
      },
      styles: {
        documentTitle: {
          fontSize: 14,
          bold: true,
          color: '#1f2a3d',
        },
        documentSubtitle: {
          fontSize: 9,
          color: '#51627a',
          margin: [0, 2, 0, 0],
        },
        sectionHeading: {
          fontSize: 10,
          bold: true,
          color: '#2e3b4e',
          margin: [0, 2, 0, 6],
        },
        labelCell: {
          bold: true,
          fillColor: '#f1f4f8',
          color: '#2e3b4e',
        },
        valueCell: {
          color: '#1f2a3d',
        },
        tableHeader: {
          bold: true,
          fillColor: '#dfe6ef',
          color: '#1f2a3d',
        },
        tableHeaderCenter: {
          bold: true,
          alignment: 'center',
          fillColor: '#dfe6ef',
          color: '#1f2a3d',
          fontSize: 8,
        },
        centerCell: {
          alignment: 'center',
          color: '#1f2a3d',
        },
      },
      info: {
        title: 'Reservation ' + config.referenceNumber,
        author: config.appTitle,
        subject: config.reservationDetailsTitle,
        creator: 'pdfmake',
      },
    };
  }

  function init() {
    const config = getConfig();
    const pdfMakeInstance = window.pdfMake;

    if (!config || !pdfMakeInstance) {
      return;
    }

    document.querySelectorAll('.btnPDF').forEach(function (btn) {
      btn.addEventListener('click', async function (e) {
        e.preventDefault();
        const previewWindow = window.open('', '_blank');
        const attributesData = await loadCustomAttributesData();
        const logoDataUrl = await loadImageDataUrl(config.logoUrl);
        const documentDefinition = buildReservationPdfDefinition(config, logoDataUrl, attributesData);

        if (previewWindow) {
          pdfMakeInstance.createPdf(documentDefinition).open({}, previewWindow);
          return;
        }

        pdfMakeInstance.createPdf(documentDefinition).open();
      });
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
