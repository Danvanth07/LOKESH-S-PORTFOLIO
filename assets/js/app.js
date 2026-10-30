/**
 * Lokesh Pon - Civil Engineering Portfolio
 * Vue 3 Application Controller
 */

const { createApp, ref, computed, onMounted } = Vue;

const app = createApp({
  setup() {
    // Theme State
    const currentTheme = ref(localStorage.getItem('loki_theme') || 'dark');
    const mobileMenuOpen = ref(false);

    // Main Portfolio Data
    const portfolio = ref(window.INITIAL_PORTFOLIO_DATA || null);
    const isLoading = ref(!window.INITIAL_PORTFOLIO_DATA);

    // Filters and Modals
    const activeSkillTab = ref('all');
    const activeProjectFilter = ref('all');
    const selectedProject = ref(null);
    const selectedCert = ref(null);
    const lightboxImage = ref(null);

    // Cover Photo Upload Modal State
    const coverModalOpen = ref(false);
    const uploadType = ref('coverPhoto'); // 'coverPhoto' or 'avatar'
    const selectedFile = ref(null);
    const previewUrl = ref(null);
    const isUploading = ref(false);
    const uploadMessage = ref('');
    const uploadError = ref('');

    // Contact Form State
    const contactForm = ref({
      name: '',
      email: '',
      phone: '',
      subject: 'Inquiry regarding Civil Engineering Opportunity',
      message: ''
    });
    const contactSubmitting = ref(false);
    const contactStatus = ref(null); // { success: boolean, message: string }

    // Toggle Theme
    const toggleTheme = () => {
      currentTheme.value = currentTheme.value === 'dark' ? 'light' : 'dark';
      document.documentElement.setAttribute('data-theme', currentTheme.value);
      localStorage.setItem('loki_theme', currentTheme.value);
    };

    // Fetch live data from PHP API
    const loadData = async () => {
      try {
        const res = await fetch('api/data.php');
        if (res.ok) {
          const data = await res.json();
          portfolio.value = data;
        }
      } catch (err) {
        console.warn('API fetch warning, using loaded data:', err);
      } finally {
        isLoading.value = false;
      }
    };

    // Filtered Projects
    const filteredProjects = computed(() => {
      if (!portfolio.value || !portfolio.value.projects) return [];
      if (activeProjectFilter.value === 'all') return portfolio.value.projects;
      return portfolio.value.projects.filter(p => {
        return p.software.some(s => s.toLowerCase().includes(activeProjectFilter.value.toLowerCase())) ||
               p.category.toLowerCase().includes(activeProjectFilter.value.toLowerCase());
      });
    });

    // Modals Handlers
    const openProjectModal = (proj) => {
      selectedProject.value = proj;
      document.body.style.overflow = 'hidden';
    };

    const closeProjectModal = () => {
      selectedProject.value = null;
      document.body.style.overflow = '';
    };

    const openLightbox = (src, caption) => {
      lightboxImage.value = { src, caption };
      document.body.style.overflow = 'hidden';
    };

    const closeLightbox = () => {
      lightboxImage.value = null;
      if (!selectedProject.value && !selectedCert.value && !coverModalOpen.value) {
        document.body.style.overflow = '';
      }
    };

    const openCertModal = (cert) => {
      selectedCert.value = cert;
      document.body.style.overflow = 'hidden';
    };

    const closeCertModal = () => {
      selectedCert.value = null;
      document.body.style.overflow = '';
    };

    // Cover / Avatar Upload Handlers
    const openUploadModal = (type = 'coverPhoto') => {
      uploadType.value = type;
      selectedFile.value = null;
      previewUrl.value = null;
      uploadMessage.value = '';
      uploadError.value = '';
      coverModalOpen.value = true;
      document.body.style.overflow = 'hidden';
    };

    const closeUploadModal = () => {
      coverModalOpen.value = false;
      document.body.style.overflow = '';
    };

    const onFileSelected = (event) => {
      const file = event.target.files[0];
      if (!file) return;

      if (!file.type.match('image.*')) {
        uploadError.value = 'Please select a valid image file (JPG, PNG, WEBP).';
        return;
      }

      if (file.size > 15 * 1024 * 1024) {
        uploadError.value = 'File size is too large (max 15MB).';
        return;
      }

      uploadError.value = '';
      selectedFile.value = file;
      previewUrl.value = URL.createObjectURL(file);
    };

    const submitUpload = async () => {
      if (!selectedFile.value) {
        uploadError.value = 'Please select an image file first.';
        return;
      }

      isUploading.value = true;
      uploadError.value = '';
      uploadMessage.value = '';

      const formData = new FormData();
      formData.append('photo', selectedFile.value);
      formData.append('type', uploadType.value);

      try {
        const response = await fetch('api/upload.php', {
          method: 'POST',
          body: formData
        });

        const result = await response.json();

        if (response.ok && result.success) {
          uploadMessage.value = result.message || 'Image uploaded successfully!';
          // Update local reactive state immediately
          if (uploadType.value === 'avatar') {
            portfolio.value.profile.avatar = result.url + '?t=' + Date.now();
          } else {
            portfolio.value.profile.coverPhoto = result.url + '?t=' + Date.now();
          }
          setTimeout(() => {
            closeUploadModal();
          }, 1600);
        } else {
          uploadError.value = result.error || 'Failed to upload photo.';
        }
      } catch (err) {
        uploadError.value = 'An error occurred during upload. Please check your PHP server.';
        console.error(err);
      } finally {
        isUploading.value = false;
      }
    };

    // Contact Form Handler
    const submitContact = async () => {
      contactSubmitting.value = true;
      contactStatus.value = null;

      try {
        const response = await fetch('api/contact.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(contactForm.value)
        });

        const result = await response.json();

        if (response.ok && result.success) {
          contactStatus.value = {
            success: true,
            message: result.message
          };
          contactForm.value = {
            name: '',
            email: '',
            phone: '',
            subject: 'Inquiry regarding Civil Engineering Opportunity',
            message: ''
          };
        } else {
          contactStatus.value = {
            success: false,
            message: result.message || result.error || 'Failed to submit inquiry.'
          };
        }
      } catch (err) {
        contactStatus.value = {
          success: false,
          message: 'Network or server error. Please try again or email directly to ' + portfolio.value.profile.email
        };
      } finally {
        contactSubmitting.value = false;
      }
    };

    // Download Handler
    const downloadResume = (type = 'resume') => {
      window.location.href = `api/download.php?file=${type}`;
    };

    const scrollToSection = (id) => {
      mobileMenuOpen.value = false;
      const el = document.getElementById(id);
      if (el) {
        el.scrollIntoView({ behavior: 'smooth' });
      }
    };

    onMounted(() => {
      document.documentElement.setAttribute('data-theme', currentTheme.value);
      loadData();
    });

    return {
      currentTheme,
      mobileMenuOpen,
      portfolio,
      isLoading,
      activeSkillTab,
      activeProjectFilter,
      filteredProjects,
      selectedProject,
      selectedCert,
      lightboxImage,
      coverModalOpen,
      uploadType,
      selectedFile,
      previewUrl,
      isUploading,
      uploadMessage,
      uploadError,
      contactForm,
      contactSubmitting,
      contactStatus,
      toggleTheme,
      openProjectModal,
      closeProjectModal,
      openLightbox,
      closeLightbox,
      openCertModal,
      closeCertModal,
      openUploadModal,
      closeUploadModal,
      onFileSelected,
      submitUpload,
      submitContact,
      downloadResume,
      scrollToSection
    };
  }
});

app.mount('#app');

